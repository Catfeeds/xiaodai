<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/12/8
 * Time: 11:22
 */

namespace Crm\Controller;


class ItemController extends BaseController
{
    public function index(){

    }

    public function additem($id=""){

        if($id){
            $item=M('item')->where(array('id'=>$id))->find();
            $members=explode(',',$item['member']);
            $members=array_filter($members);
            $this->assign('members',$members);
            $this->assign('item',$item);
        }

        $manageid=get_manageid();
        $staff=M('staff')->where(array('id'=>$manageid))->find();
        $this->assign('staff',$staff);

        $stafflist=M('staff')->where(array('status'=>1))->select();
        $this->assign('stafflist',$stafflist);

        $where=array();
        $where['status']=1;
        $custom=M('member')->where(array('status'=>1,'staffid'=>$manageid))->select();
        $this->assign('custom',$custom);
        $this->assign('title','新建项目');
        $this->display();
    }

    public function getcustom(){
        $where=array();
        $keyword=$_POST['keyword'];
        $where['telephone']=array('neq','');

        if(!isN($keyword)){
            $map=array();
            $map['company']=array('like','%'.$keyword.'%');
            $map['userreal']=array('like','%'.$keyword.'%');
            $map['telephone']=array('like','%'.$keyword.'%');
            $map['address']=array('like','%'.$keyword.'%');
            $map['_logic']='or';
            $where['_complex']=$map;
        }
        $manageid=get_manageid();
        $where['staffid']=$manageid;
        $list=M('member')->where($where)->limit(6)->select();
        $arr="";
        foreach($list as $k=>$v){
            $liname=$v['company'].'('.$v['userreal'].')';
            $liname=str_replace($keyword,'<span style="color: black;">'.$keyword.'</span>',$liname);
            $arr.='<li style="padding: 5px;" data-id="'.$v['id'].'">'.$liname.'</li>';
        }
        $this->ajaxReturn($arr);
    }


    public function subitem(){
        $data=$_POST;
        $id=$data['id'];
        $data['member']=','.$data['member'];
        if($id){
            $save=M('item')->where(array('id'=>$data['id']))->data($data)->save();
        }else{
            $save=M('item')->data($data)->add();
        }
        $reuslt=array();
        if($save===false){
            $result['status']=0;
            $result['info']='提交失败，请稍后重试';
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']='提交成功';
        $this->ajaxReturn($result);

    }


    public function itemlist(){

        $this->assign('title','我的项目');
        $this->display();
    }

    public function getLists($p = 1,$field='',$sort='',$act='',$keyword='',$status=''){
        $manageid=get_manageid();
        $where = array ();
        $where['member']=array('like','%,'.$manageid.",%");
        if($status){
            $where['status']=$status;
        }
        if(!isN($keyword)){
            $map=array();
            $map['name']=array('like','%'.$keyword.'%');
            $map['mastername']=array('like','%'.$keyword.'%');
            $map['remark']=array('like','%'.$keyword.'%');
            $map['_logic']='or';
            $where['_complex']=$map;
        }


        $order='id desc';
        if($sort){
            $order=$field.' '.$sort;
        }
        $row = 20;

        $list=M('item')->where($where)->page($p,$row)->order($order)->select();


        $this->assign ( 'list', $list );
        $this->assign ( 'p', $p );
        $this->display ( 'getLists' );


    }

    public function view($id=""){

        $db=M('item')->where(array('id'=>$id))->find();

        $this->assign('db',$db);

        //跟进记录
        $record=M('staff_follow')->where(array('memberid'=>$db['customid']))->order('addtime desc')->select();
        $this->assign('record',$record);

        $this->assign('title',$db['name']);
        $this->display();
    }

}