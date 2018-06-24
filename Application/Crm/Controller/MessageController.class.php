<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/22
 * Time: 14:37
 */

namespace Crm\Controller;


class MessageController extends BaseController
{
    public function index(){
		$cate=M('category_news')->where(array('inner'=>1,'status'=>1))->select();
        $ids=array();
        foreach($cate as $k=>$v){
            $ids[$k]=$v['id'];
        }

        $list=M('content_news')->where(array('pid'=>array('in',$ids),'status'=>1))->order('addtime desc')->select();

		 foreach($list as $key=>$val){
            $staffid=get_manageid();
            $ids=M('message_record')->where(array('staffid'=>$staffid))->field('newsid')->select();
            $idarr=array();
            foreach($ids as $ks=>$vs){
                $idarr[$ks]=$vs['newsid'];
            }
           if(in_array($val['id'],$idarr)){
               $list[$key]['read']=1;
           }else{
               $list[$key]['read']=0;
           }
        }


        $this->assign('list',$list);
		
		
        $this->assign('list',$list);

        $this->assign('title','消息通知');
        $this->display();
    }

    public function view($id=''){

        $db=M('content_news')->where(array('id'=>$id))->find();
        $this->assign('db',$db);

		 //添加阅读记录
        $data=array();
        $data['staffid']=get_manageid();
        $data['newsid']=$id;  
        $find=M('message_record')->where($data)->find();
        if(!$find){
			M('message_record')->data($data)->add();
        }

		
        $this->assign('title',$db['title']);
        $this->display();
    }
}