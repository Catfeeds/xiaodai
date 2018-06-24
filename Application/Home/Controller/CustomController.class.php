<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2016/12/27
 * Time: 18:37
 */

namespace Home\Controller;


class CustomController extends BaseController
{
    public function index(){




        $this->assign ( 'title', '找企业' );
        $this->assign ( 'keywords','找企业' );
        $this->assign ( 'description', '找企业' );

        $this->display ();

    }

    public function getcustomList($p=1,$nickname='',$need='',$have='',$type=''){
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $where = array ();
        if($nickname){
            $where['nickname']=array('like','%'.$nickname.'%');
            $where['userreal']=array('like','%'.$nickname.'%');
            $where['company']=array('like','%'.$nickname.'%');
            $where['_logic']='OR';

        }
        if($need){
            $where['need']=array('like','%'.$need.'%');
        }
        if($have){
            $where['have']=array('like','%'.$have.'%');
        }



        $where1 ['id'] =array('neq',get_memberid());
        if($where)
            $where1['_complex'] = $where;


        $list = M ( "member" )->where ( $where1 )->order ( 'id desc' )->page ( $p, $row )->select ();
//        dump(M ( "member" )->getLastSql());

        $this->assign('type',$type);

        $this->assign ( "list", $list );
        $this->assign ( 'p', $p );
        $this->display ();
    }

    public function view($id=''){
        $find=M('member')->where(array('id'=>$id))->find();
        if(!$find){
            go_404();
        }
        $where=array();
        $where['memberid']=get_memberid();
        $where['customid']=$id;
        $exists=M('member_custom')->where($where)->find();
        if($exists){
            $issub=1;
        }else{
            $issub=0;
        }
//        dump($issub);


        $find['have']=explode('^',$find['have']);
        $find['need']=explode('^',$find['need']);

        $this->assign("issub",$issub);
        $this->assign('find',$find);

        $this->assign('title',$find['title']);
        $this->display();
    }


    public function subscribe(){
        $customid=$_POST['id'];
        $type=$_POST['type'];//1：关注，2：取消关注
        $result=array();
        if($type==1){
            $data['memberid']=get_memberid();
            $data['customid']=$customid;
            $data['addtime']=date('Y-m-d H:i:s');
            $add=M('member_custom')->data($data)->add();
            if($add===false){
                $result['status']=0;
                $result['info']="关注失败，请稍后重试";
                $this->ajaxReturn($result);
            }
            $result['status']=1;
            $result['info']="关注成功";
            $this->ajaxReturn($result);
        }else{
            $where=array();
            $where['memberid']=get_memberid();
            $where['customid']=$customid;
            $find=M('member_custom')->where($where)->find();
            if(!$find){
                $result['status']=0;
                $result['info']="取消关注失败，请稍后重试";
                $this->ajaxReturn($result);
            }
            $set=M('member_custom')->where($where)->delete();
            if($set===false){
                $result['status']=0;
                $result['info']="取消关注失败，请稍后重试";
                $this->ajaxReturn($result);
            }
            $result['status']=1;
            $result['info']="取消关注成功";
            $this->ajaxReturn($result);
        }


    }


    public function findopt(){

        $this->assign ( 'title', '找商机' );
        $this->assign ( 'keywords','找商机' );
        $this->assign ( 'description', '找商机' );

        $this->display ();
    }

    public function findpro(){

        $this->assign ( 'title', '找产品' );
        $this->assign ( 'keywords','找产品' );
        $this->assign ( 'description', '找产品' );

        $this->display ();
    }

}