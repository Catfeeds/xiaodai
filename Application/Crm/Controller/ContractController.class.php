<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/12/14
 * Time: 15:47
 */

namespace Crm\Controller;


class ContractController extends BaseController
{

    public function addcontract($id=""){

        if($id){
            $item=M('contract')->where(array('id'=>$id))->find();
            $contractimages=explode(',',$item['contractimages']);
            $contractimages=array_filter($contractimages);
            $this->assign('contractimages',$contractimages);
            $this->assign('item',$item);
        }

        $manageid=get_manageid();
        $staff=M('staff')->where(array('id'=>$manageid))->find();
        $this->assign('staff',$staff);


        $where=array();
        $where['status']=1;
        $custom=M('member')->where(array('status'=>1,'staffid'=>$manageid))->select();
        $this->assign('custom',$custom);
        $this->assign('title','新建合同');
        $this->display();
    }

    public function view($id=""){

        if($id){
            $item=M('contract')->where(array('id'=>$id))->find();
            $contractimages=explode(',',$item['contractimages']);
            $contractimages=array_filter($contractimages);
            $this->assign('contractimages',$contractimages);
            $this->assign('item',$item);
        }

        $manageid=get_manageid();
        $staff=M('staff')->where(array('id'=>$manageid))->find();
        $this->assign('staff',$staff);

        $this->assign('manageid',$manageid);
        $where=array();
        $where['status']=1;
        $custom=M('member')->where(array('status'=>1,'staffid'=>$manageid))->select();
        $this->assign('custom',$custom);
        $this->assign('title','新建合同');
        $this->display();
    }

    public function saveimg(){
        $pic=$_POST['pic'];
        $result=array();
        $picurl=base64Toimg($pic);
        if(!$picurl){
            $result['status']=0;
            $result['info']="上传失败";
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']=$picurl;
        $this->ajaxReturn($result);
    }

    public function subcontract(){
        $data=$_POST;
        $id=$_POST['id'];
        unset($data['id']);
        if($id){
            $save=M('contract')->where(array('id'=>$id))->data($data)->save();
        }else{
            $save=M('contract')->data($data)->add();
        }
        $result=array();
        if($save===false){
            $result['status']=0;
            $result['info']="提交合同失败，请稍后重试";
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['info']="提交合同成功";
        $this->ajaxReturn($result);
    }


    public function lists(){

        $all=M('contract')->where(array('status'=>array('neq',2)))->field('sum(amount) as amount,sum(getamount) as getamount')->select();
        $allamount=$all[0]['amount'];
        $getallamount=$all[0]['getamount'];
        $this->assign('allamount',$allamount);
        $this->assign('getallamount',$getallamount);

        $this->assign('title','合同管理');
        $this->display();
    }

    public function getlists($p = 1,$field='',$sort='',$act='',$keyword='',$status=''){

        $contract_timeout=C('config.CONTRACT_TIMEOUT');
        $this->assign('contract_timeout',$contract_timeout);

        $manageid=get_manageid();
        $where = array ();
        if($act=='my'){
            $where['masterid']=$manageid;
        }
        if($status){
            $where['status']=$status;
        }
        if(!isN($keyword)){
            $map=array();
            $map['name']=array('like','%'.$keyword.'%');
            $map['mastername']=array('like','%'.$keyword.'%');
            $map['remark']=array('like','%'.$keyword.'%');
            $map['no']=array('like','%'.$keyword.'%');
            $map['_logic']='or';
            $where['_complex']=$map;
        }


        $order='id desc';
        if($sort){
            $order=$field.' '.$sort;
        }
        $row = 20;

        $list=M('contract')->where($where)->page($p,$row)->order($order)->select();

        $now=date('Y-m-d');
        foreach($list as $k=>$v){
            $left=diffBetweenTwoDays($now,$v['end']);
            if(strtotime($now)>strtotime($v['end'])){
                $list[$k]['guoqi']=1;
                $list[$k]['left']=0;
            }else{
                if($left>0)
                    $list[$k]['left']=$left;
            }
        }


        $this->assign ( 'list', $list );
        $this->assign ( 'p', $p );
        $this->display ( 'getLists' );


    }



}