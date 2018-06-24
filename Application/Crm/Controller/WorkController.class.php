<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/12/6
 * Time: 9:35
 */

namespace Crm\Controller;


class WorkController extends BaseController
{
    protected $wechatObj;
    public function index(){
        $manageid=get_manageid();
        //我的工作日程
        $list=M('work')->where(array('staffid'=>$manageid))->order('addtime desc')->select();
        $now=date('Y-m-d');
        foreach($list as $k=>$v){
            $left=diffBetweenTwoDays($now,$v['end']);

            if($left>=0)
                $list[$k]['left']=$left;
            if(strtotime($now)>strtotime($v['end']))
                $list[$k]['guoqi']=1;
        }
        $this->assign('list',$list);
        $this->assign('type',0);
        $this->assign('title','我的工作日程');
        $this->display();
    }

    public function addwork($id=""){

        $mpid = get_mp_str();
        $signPackage = getShareSign ( $mpid );
        $this->assign ( 'signPackage', $signPackage );
        if($id){
            $db=M('work')->where(array('id'=>$id))->find();
            $this->assign('db',$db);
        }

        $this->assign('id',$id?$id:0);
        $this->assign('title','添加工作日程');
        $this->display();
    }

    public function subwork(){
        $data=$_POST;
        $id=$data['id'];
        unset($data['id']);
        $manageid=get_manageid();
        $data['staffid']=$manageid;
        $data['date']=date("Y-m-d");
        $result=array();

        if(strtotime($data['start'])>strtotime($data['end'])){
            $result['status']=0;
            $result['info']="工作日程开始时间不能大于结束时间";
            $this->ajaxReturn($result);
        }

        $days=diffBetweenTwoDays($data['start'],$data['end']);
        if($days<7){
            $data['type']=1;
        }
        if($days>=7 && $days<=14){
            $data['type']=2;
        }
        if($days>14){
            $data['type']=3;
        }

        if($id){
            $add=M('work')->where(array('id'=>$id))->data($data)->save();
        }else{
            $add=M('work')->data($data)->add();
        }




        if($add===false){
            $result['status']=0;
            $result['info']="更新工作日程失败，请稍后重试";
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['info']="更新工作日程成功";

        M('staff')->where(array('id'=>$manageid))->setField(array('workdate'=>date('Y-m-d'),'noticedate'=>date('Y-m-d')));

        sendworkmsg();

        $this->ajaxReturn($result);
    }

    public function adminwork(){
        if(IS_POST){
            $keyword=$_POST['keyword'];
            if($keyword){
                $where['name']=array('like','%'.$keyword.'%');
            }
            $stafflist=M('staff')->where($where)->select();
            $ids=array();
            foreach($stafflist as $k=>$v){
                $ids[$k]=$v['id'];
            }
        }
        if(count($ids)>0){
            $map['staffid']=array('in',$ids);
        }
        $manageid=get_manageid();
        $list=M('work')->where($map)->order('addtime desc')->select();

        $now=date('Y-m-d');

        foreach($list as $k=>$v){
            $praise=M('work_praise')->where(array('workid'=>$v['id'],'staffid'=>$manageid))->find();
            if($praise){
                $list[$k]['praise']=1;
            }else{
                $list[$k]['praise']=0;
            }

            $praisenum=M('work_praise')->where(array('workid'=>$v['id']))->count();
            $list[$k]['praisenum']=$praisenum?$praisenum:0;

            $commentnum=M('work_comment')->where(array('workid'=>$v['id']))->count();
            $list[$k]['commentnum']=$commentnum?$commentnum:0;


            $left=diffBetweenTwoDays($now,$v['end']);

            if($left>=0)
                $list[$k]['left']=$left;
            if(strtotime($now)>strtotime($v['end']))
                $list[$k]['guoqi']=1;
        }
        $this->assign('list',$list);
        $this->assign('type',1);
        $this->assign('title','同事工作日程');
        $this->display();
    }

    public function workcomment($id=""){
        $db=M('work')->where(array('id'=>$id))->find();
        $manageid=get_manageid();
        $praise=M('work_praise')->where(array('workid'=>$id,'staffid'=>$manageid))->find();
        if($praise){
            $db['praise']=1;
        }else{
            $db['praise']=0;
        }

        $praisenum=M('work_praise')->where(array('workid'=>$id))->count();
        $db['praisenum']=$praisenum?$praisenum:0;

        $commentnum=M('work_comment')->where(array('workid'=>$id))->count();
        $db['commentnum']=$commentnum?$commentnum:0;

        $this->assign('db',$db);
        $list=M('work_comment')->where(array('workid'=>$id))->order('addtime desc')->select();
        foreach($list as $k=>$v){
            if($v['staffid']==$manageid){
                $list[$k]['dele']=1;
            }
        }

        $this->assign('list',$list);
        $this->assign('title','评论工作日程');
        $this->display();
    }
    public function comment(){
        $id=$_POST['id'];
        $manageid=get_manageid();
        $content=$_POST['content'];

        $data=array();
        $data['workid']=$id;
        $data['staffid']=$manageid;
        $data['content']=$content;
        $add=M('work_comment')->data($data)->add();
        if($add===false){
            $result['status']=0;
            $result['info']="评论失败，请稍后重试";
            $this->ajaxReturn($result);
        }

        $staff=M('staff')->where(array('id'=>$manageid))->find();
        $addtime=date("Y-m-d H:i:s");
        $name=$staff['name'];
        if($staff['headimgurl']){
            $result['onename']="/Public/Crm/images/nouser.png";
        }else{
            $result['onename']=$staff['headimgurl'];
        }

        $result['addtime']=$addtime;
        $result['name']=$name;
        $result['id']=$add;
        $result['status']=1;
        $result['info']="评论成功";
        $this->ajaxReturn($result);
    }

    public function deletecomment(){
        $id=$_POST['id'];
        $manageid=get_manageid();
        $delete=M('work_comment')->where(array('id'=>$id,'staffid'=>$manageid))->delete();
        $result=array();
        if($delete===false){
            $result['status']=0;
            $result['info']="删除失败，请稍后重试";
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['info']="删除成功";
        $this->ajaxReturn($result);

    }


    public function deletework(){
        $id=$_POST['id'];
        $manageid=get_manageid();
        $delete=M('work')->where(array('id'=>$id,'staffid'=>$manageid))->delete();
        $result=array();
        if($delete===false){
            $result['status']=0;
            $result['info']="操作失败，请稍后重试";
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['info']="操作成功";
        $this->ajaxReturn($result);
    }

    public function setpraise(){
        $id=$_POST['id'];
        $manageid=get_manageid();
        $work=M('work')->where(array('id'=>$id))->find();

        $find=M('work_praise')->where(array('workid'=>$id,'staffid'=>$manageid))->find();

        if($find){
            //删除点赞记录
            $act=M('work_praise')->where(array('workid'=>$id,'staffid'=>$manageid))->delete();
            M('staff')->where(array('id'=>$work['staffid']))->setDec('praisenum');
            $praise=0;
        }
        else{
            //添加点赞记录
            $data=array();
            $data['workid']=$id;
            $data['staffid']=$manageid;
            $act=M('work_praise')->data($data)->add();
            M('staff')->where(array('id'=>$work['staffid']))->setInc('praisenum');
            $praise=1;
        }
        $result=array();
        if($act===false){
            $result['status']=0;
            $result['info']="操作失败，请稍后重试";
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['info']="操作成功";
        $result['praise']=$praise;
        $this->ajaxReturn($result);

    }

    public function getdaywork(){
        $searchdate=$_POST['searchdate'];
        $type=$_POST['type'];//0-我的工作日程，2-全部工作日程
        $where=array();
        $where['start']=array('elt',$searchdate);
        $where['end']=array('egt',$searchdate);
        if($type==0){
            $where['staffid']=get_manageid();
        }
        $count=M('work')->where($where)->count();
        $count=$count?$count:0;
        $this->ajaxReturn($count);
    }

    public function getwork(){

        $date=$_POST['date'];
        $type=$_POST['type'];
        $keyword=$_POST['keyword'];
        $where=array();
        $where['start']=array('elt',$date);
        $where['end']=array('egt',$date);
        if($type==0){
            $manageid=get_manageid();
            //我的工作日程
            $where['staffid']=$manageid;

            $list=M('work')->where($where)->order('addtime desc')->select();

            $now=date('Y-m-d');
            foreach($list as $k=>$v){
                $left=diffBetweenTwoDays($now,$v['end']);

                if($left>=0)
                    $list[$k]['left']=$left;
                if(strtotime($now)>strtotime($v['end']))
                    $list[$k]['guoqi']=1;
            }
            $this->assign('list',$list);
        }else{
            if($keyword){
                $map['name']=array('like','%'.$keyword.'%');
            }
            $stafflist=M('staff')->where($map)->select();
            $ids=array();
            foreach($stafflist as $k=>$v){
                $ids[$k]=$v['id'];
            }

            if(count($ids)>0){
                $where['staffid']=array('in',$ids);
            }
            $manageid=get_manageid();
            $list=M('work')->where($where)->order('addtime desc')->select();

            $now=date('Y-m-d');

            foreach($list as $k=>$v){
                $praise=M('work_praise')->where(array('workid'=>$v['id'],'staffid'=>$manageid))->find();
                if($praise){
                    $list[$k]['praise']=1;
                }else{
                    $list[$k]['praise']=0;
                }

                $praisenum=M('work_praise')->where(array('workid'=>$v['id']))->count();
                $list[$k]['praisenum']=$praisenum?$praisenum:0;

                $commentnum=M('work_comment')->where(array('workid'=>$v['id']))->count();
                $list[$k]['commentnum']=$commentnum?$commentnum:0;


                $left=diffBetweenTwoDays($now,$v['end']);

                if($left>=0)
                    $list[$k]['left']=$left;
                if(strtotime($now)>strtotime($v['end']))
                    $list[$k]['guoqi']=1;
            }

            $this->assign('type',$type);

            $this->assign('list',$list);
            $this->assign('type',1);
        }
        $worknum=count($list);
        $this->assign('worknum',$worknum);

        $this->display();
    }

}