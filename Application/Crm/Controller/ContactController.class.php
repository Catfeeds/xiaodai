<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/20
 * Time: 13:21
 */

namespace Crm\Controller;


class ContactController extends BaseController
{
    public function index(){
        $manageid=get_manageid();
        $where=array();
        $where['mma.staffid']=$manageid;
        $where['mma.status']=1;
        $count=M('member as mm')->join('my_member_apply as mma on mma.memberid = mm.id')->where($where)->count();
        $count=$count?$count:0;
        $staffcount=M('staff')->where(array('status'=>1,'id'=>array('neq',$manageid)))->count();
        $staffcount=$staffcount?$staffcount:0;
        $this->assign('count',$count);
        $this->assign('staffcount',$staffcount);
        $this->assign('title','通讯录');
        $this->display();

    }

    public function getLists($p = 1,$keyword='',$act=''){
        if($act==1){
            $where=array();
            $where["name"]=array('like','%'.$keyword.'%');
            $where['status']=1;
            $where['id']=array('neq',get_manageid());
            $row = 20;
            $list = M ( "staff" )->where ( $where )->page ( $p, $row )->order('name asc')->select ();

        }else{
            $manageid=get_manageid();
            $tblname = 'member as mm';
            $where = array ();
            $where['mma.staffid']=$manageid;
            $where['mma.status']=1;
            $map=array();
            if(!isN($keyword)){
                $map['mm.userreal']=array('like','%'.$keyword.'%');
                $map['mm.telephone']=array('like','%'.$keyword.'%');
                $map['mm.username']=array('like','%'.$keyword.'%');
                $map['_logic']='or';
                $where['_complex'] = $map;
            }

            $field="mm.*,mma.staffid";

            $row = 20;
            $list = M ( $tblname )->join('my_member_apply as mma on mma.memberid = mm.id')->where ( $where )->field($field)->page ( $p, $row )->order('mm.userreal asc')->select ();
        }

		
        $this->assign ( 'list', $list );
        $this->assign ( 'act', $act );
        $this->assign ( 'p', $p );
        $this->display ( 'getLists' );
    }


}