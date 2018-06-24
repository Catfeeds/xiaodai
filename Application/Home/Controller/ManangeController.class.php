<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/8/11
 * Time: 19:21
 */

namespace Home\Controller;

use Think\Controller;

class ManangeController extends Controller
{
    public function detail($p = 1,$order='',$keyword=''){

        $where = array ();
        $where['status']=1;
        if(!isN($keyword)){
            $where['title']=array('like','%'.$keyword.'%');
        }
        $this->assign('keyword',$keyword);

        if(!$order){
            $order="hits";
        }
        // 表名
        $tblname = 'content_news';
        $row = 5;
        $rs = M ( $tblname )->where ( $where )->order ( $order.' desc' )->page ( $p, $row );
        $list = $rs->select ();



        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%UP_PAGE% %DOWN_PAGE%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign('p',$p);
        $this->assign('order',$order);

        $this->assign('title','文章分析');
        $this->display();
    }

    public function article($id=''){
        $db=M('content_news')->where(array('id'=>$id))->find();
        $this->assign('db',$db);

        //关键传播节点
        $fields="sum(av.num) as num,av.frommemberid,mm.nickname,mm.headimgurl";
        $members=M('article_view as av')->join('my_member as mm on mm.id=av.frommemberid')->where(array('av.frommemberid'=>array('gt',0),'av.articleid'=>$id))->field($fields)->order('num desc')->limit(4)->group('frommemberid')->select();

        $this->assign('members',$members);

        $this->assign('id',$id);
        $this->assign('title',$db['title']);
        $this->display();
    }
	
	  public function active(){

        $keyword=$_POST['keyword'];
        $where = array ();

        $where['status']=1;

        if (! isN ( $keyword )) {
            $where ['title'] = array (
                'like',
                '%' . $keyword . '%'
            );
        }

        // 表名
        $tblname = 'content_active';

        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' );
        $list = $rs->select ();
        foreach($list as $key=>$val){
           if($val['type']==1){
                $nums=M('content_active_member')->where(array('activeid'=>$val['id'],'status'=>1))->count();
            }else{
                $nums=M('content_active_memberp')->where(array('activeid'=>$val['id'],'status'=>1))->count();
            }
            $list[$key]['nums']=$nums;
        }

        $this->assign ( "list", $list );

        $this->assign ( "keyword", $keyword );


        $this->assign ( "title", '活动分析' );
        $this->display ();
    }


    public function activemember($id=""){
        $active=M('content_active')->where(array('id'=>$id))->find();
        if($active['type']==1){
            $nums=M('content_active_member')->where(array('activeid'=>$active['id'],'status'=>1))->count();
            $list=M('content_active_member')->where(array('activeid'=>$id,'status'=>1))->select();
        }else{
            $nums=M('content_active_memberp')->where(array('activeid'=>$active['id'],'status'=>1))->count();
            $list=M('content_active_memberp')->where(array('activeid'=>$id,'status'=>1))->select();
        }
        $active['nums']=$nums;
        $this->assign('active',$active);
        $this->assign('list',$list);
//        dump($list);die;
        $this->assign ( "title", '活动报名列表' );
        $this->display ();
    }

	public function member(){
        $keyword=$_POST['keyword'];
        $where=array();
        $where['status']=1;
        if($keyword){
            $where['nickname']=array('like','%'.$keyword.'%');
        }
        //会员列表
        $list=M('member')->where($where)->select();
        foreach($list as $k=>$v){
            $actnum1=M('content_active_member')->where(array('memberid'=>$v['id'],'status'=>1))->count();
            $actnum2=M('content_active_memberp')->where(array('memberid'=>$v['id'],'status'=>1))->count();
            $signnum=M('content_signup_record')->where(array('memberid'=>$v['id']))->count();
            if($signnum==0 && $actnum1==0 && $actnum2==0){
                unset($list[$k]);
            }else{
                $list[$k]['activenum']=$actnum1+$actnum2;
                $list[$k]['signupnum']=$signnum;
            }
        }

        $this->assign('list',$list);

        $this->assign ( "title", '会员报名签到分析' );
        $this->display();

    }


}