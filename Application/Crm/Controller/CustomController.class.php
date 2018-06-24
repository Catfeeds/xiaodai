<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/19
 * Time: 11:17
 */

namespace Crm\Controller;


class CustomController extends BaseController
{

    protected $wechatObj;

    public function index($act='',$keyword=''){

        //客户分类
        $memberstatus=M('member_status')->where(array('status'=>1))->select();
        $this->assign('memberstatus',$memberstatus);

        //客户分级
        $memberlevel=M('member_level')->where(array('status'=>1))->select();
        $this->assign('memberlevel',$memberlevel);

        //客户来源
        $membersource=M('member_source')->where(array('status'=>1))->select();
        $this->assign('membersource',$membersource);


       ////房源字典列表
       //$list=M('house_dic')->where(array('pid'=>0,'status'=>1))->select();
       //foreach($list as $key =>$val){
       //    $down=M('house_dic')->where(array('pid'=>$val['id'],'status'=>1))->select();
       //    $list[$key]['down']=$down;
       //}
       //$this->assign('housedic',$list);


        $where=array();
        $complate=getcomplatestatus();
        $where['memberstatus']=array('neq',$complate);
        if($act){
            $where['staffid']=get_manageid();
        }
        $count=M('member')->where($where)->count();
        $this->assign('count',$count?$count:0);

        $this->assign('act',$act);
        $this->assign('keyword',$keyword);
        $this->assign('title','客户');
        $this->display();
    }


    public function getLists($p = 1,$field='',$sort='',$act='',$keyword=''){
        $para=$_GET;
        $field=$para['field'];
        $p=$para['p'];
        $sort=$para['sort'];
        $act=$para['act'];
        $keyword=$para['keyword'];
        unset($para['field'],$para['p'],$para['sort'],$para['act'],$para['keyword']);

        $tblname = 'member as m';
        $where = array ();
        if(count($para)>0){
            $where=$para;
        }
        $where ['m.status'] = 1;
		
	  $where['m.telephone']=array('neq','');
		
        if(!isN($keyword)){
            $map=array();
			$map['m.company']=array('like','%'.$keyword.'%');
            $map['m.userreal']=array('like','%'.$keyword.'%');
            $map['m.telephone']=array('like','%'.$keyword.'%');
            $map['m.address']=array('like','%'.$keyword.'%');
            $map['_logic']='or';
            $where['_complex']=$map;
        }
		

		

        $order='m.lastfollowtime desc';
        if($sort){
            $order='m.'.$field.' '.$sort;
        }
        $row = 20;



        if($act=='my'){

            $where['mma.status']=1;
            $where['mma.staffid']=get_manageid();
            $field="m.*,mma.status as mstatus,mma.addtime as maddtime,mma.updatetime as mupdatetime";
            $list = M ( $tblname )->join('my_member_apply as mma on mma.memberid=m.id')->where ( $where )->field($field)->page ( $p, $row )->order($order)->select ();

        }elseif($act=="depart"){
            $mydepart=M('staff')->where(array('id'=>get_manageid()))->getField('departmentid');
            //$where['m.staffid']=get_manageid();
            $departlimit=M('staff')->where(array('id'=>get_manageid()))->getField('departlimit');
            $departlimit=json_decode($departlimit,true);
            if(count($departlimit)>0){
                $departlimit[count($departlimit)]=$mydepart;
            }else{
                $departlimit[0]=$mydepart;
            }
            $where['ms.departmentid']=array('in',$departlimit);

//            dump($where);die;
            $field="m.*,mma.status as mstatus,mma.addtime as maddtime,mma.updatetime as mupdatetime";
            $list=M($tblname)->join('my_staff as ms on ms.id=m.staffid')
                ->join('my_member_apply as mma on mma.memberid=m.id')->where ( $where )->field($field)->page ( $p, $row )->order($order)->select ();
            //$list=M($tblname)->join('my_staff as ms on ms.id=m.staffid')
            //  ->where ( $where )->page ( $p, $row )->order($order)->select ();

        }else{
            $where ['m.applystatus'] = 0;
            $where['m.staffid']=array('lt',1);
            $list = M ( $tblname )->where ( $where )->page ( $p, $row )->order($order)->select ();
        }

        foreach($list as $k=>$v){
            if($v['lastfollowtime']){
                $days=diffBetweenTwoDays($v['lastfollowtime'],date('Y-m-d H:i:s'));
            }else{
                $days=0;
            }
            $list[$k]['days']=$days;
        }


        $this->assign ( 'list', $list );


        //未成交客户数
        $where=array();
        $complate=getcomplatestatus();
        $where['memberstatus']=array('neq',$complate);
        $where['telephone']=array('neq','');
        if($act=='my'){
            $where['staffid']=get_manageid();
        }elseif($act=='depart'){
            $ids=M('staff')->where(array('departmentid'=>array('in',$departlimit)))->select();
            $inid=array();
            foreach($ids as $k=>$v){
                $inid[$k]=$v['id'];
            }
            $where['staffid']=array('in',$inid);

        }else{
            $where ['applystatus'] = 0;
            $where['staffid']=array('lt',1);
        }
        $count=M('member')->where($where)->count();
        $count=$count?$count:0;

        $this->assign ( 'num', $count );

        $this->assign ( 'p', $p );
        $this->assign ( 'act', $act );
        $this->display ( 'getLists' );


    }



    public function view($id=''){

        $manageid=get_manageid();
        $staff=M('staff')->where(array('id'=>$manageid))->find();
        $this->assign('staff',$staff);

        $member=M('member')->where(array('id'=>$id))->find();

        if($member['lastfollowtime']){
            $days=diffBetweenTwoDays($member['lastfollowtime'],date('Y-m-d H:i:s'));
        }else{
            $days=0;
        }


        $member['days']=$days;


        $this->assign('member',$member);




        //跟进记录
        $record=M('staff_follow')->where(array('memberid'=>$id))->order('addtime desc')->select();
        $this->assign('record',$record);
		
		$this->assign('manageid',$manageid);

        $this->assign('title',$member['company']);
        $this->display();
    }


    public function adminfollowrecord($keyword=""){
        $cusid=array();
        $ids=array();

        if($keyword){
            $cus=M('member')->where(array('username'=>array('like','%'.$keyword."%")))->select();
            foreach($cus as $k=>$v){
                $cusid[$k]=$v['id'];
            }

            $stafflist=M('staff')->where(array('name'=>array('like','%'.$keyword.'%')))->select();
            foreach($stafflist as $k=>$v){
                $ids[$k]=$v['id'];
            }
        }
        $where=array();
        if(count($cusid)>0 ){
            $where['memberid']=array('in',$cusid);
        }
        if(count($ids)>0){
            $where['staffid']=array('in',$ids);
        }
        $where['_logic']='or';


        //跟进记录
        $record=M('staff_follow')->where($where)->order('addtime desc')->select();
        $this->assign('record',$record);

        //跟进
        $where['type']=1;
        $genjin=M('staff_follow')->where($where)->order('addtime desc')->count();
        $this->assign('genjin',$genjin?$genjin:0);
        //带看
        $where['type']=2;
        $daikan=M('staff_follow')->where($where)->order('addtime desc')->count();
        $this->assign('daikan',$daikan?$daikan:0);


        $this->assign('keyword',$keyword);

        $this->assign('title','跟进记录');
        $this->display();
    }


    public function applymember(){
        $memberid=$_POST['id'];
        $manageid=get_manageid();


        //已完成客户状态
        $complate=getcomplatestatus();

        //当前客户数,排除已完成的客户
        $nowcusnum=M('member')->where(array('staffid'=>$manageid,'status'=>1,'memberstatus'=>array('neq',$complate)))->count();
        $nowcusnum=$nowcusnum?$nowcusnum:0;

        ////最大客户数
        //$mostcusnum=C('config.MOST_CUS_NUM');
        //if($nowcusnum>=$mostcusnum){
        //    $result['status']=0;
        //    $result['info']='您所拥有的私有客户已达到最高标准'.$mostcusnum.'人。';
        //    $this->ajaxReturn($result);
        //}

        $result=array();
        $find=M('member')->where(array('id'=>$memberid))->find();
        if($find['applystatus'] > 0){
            $result['status']=0;
            $result['info']='该客户已经被其他员工添加为私有客户，请选择其他客户';
            $this->ajaxReturn($result);
        }

        $set=M('member')->where(array('id'=>$memberid))->setField('applystatus',1);

        if($set===false){
            $result['status']=0;
            $result['info']='申请失败，请稍后再试';
            $this->ajaxReturn($result);
        }

        $data=array();
        $data['memberid']=$memberid;
        $data['staffid']=$manageid;
        $data['status']=0;
        $apped=M('member_apply')->where(array('memberid'=>$memberid,'staffid'=>$manageid))->find();
        if($apped){
            $add=M('member_apply')->where(array('id'=>$apped['id']))->data($data)->save();
        }else{
            $add=M('member_apply')->data($data)->add();
        }

        if($add===false){
            $result['status']=0;
            $result['info']='申请失败，请稍后再试';
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']='申请成功,请等待管理员审核';
        $this->ajaxReturn($result);
    }


    public function detail($id=''){
        $member=M('member')->where(array('id'=>$id))->find();
        $extendinfo=json_decode($member['extendinfo'],true);
        $this->assign('extendinfo',$extendinfo);
        $this->assign('member',$member);

        ////房源字典列表
        //$list=M('house_dic')->where(array('pid'=>0,'status'=>1))->select();
        //foreach($list as $key =>$val){
        //    $down=M('house_dic')->where(array('pid'=>$val['id'],'status'=>1))->select();
        //    $list[$key]['down']=$down;
        //}
        //$this->assign('housedic',$list);

        $manageid=get_manageid();
        $this->assign('manageid',$manageid);
        $this->assign('title',$member['company']);

        $this->display();
    }

    public function alterdetail($id=''){
        $manageid=get_manageid();
        if(IS_POST){

            $data=$_POST;

            $newcol=$data['cols'];
            $sql="desc my_member";
            $oldcollist=M()->query($sql);
            $oldcol=array();
            foreach($oldcollist as $kn=>$vn){
                $oldcol[$kn]=$vn['field'];
            }
            foreach($newcol as $k=>$v){
                if(!in_array($v,$oldcol)){
                    //添加字段
                    $sql = "ALTER TABLE ".C('DB_PREFIX')."member ADD ".$v." VARCHAR(255);";
                    M() -> execute($sql);
                }

            }


            $id=$_POST['id'];

			
			 if(!$data['company']){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo "<script type='text/javascript'>alert('请输入公司 名称');window.history.back();</script>";
                exit();
            }

            if(!$data['userreal']){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo "<script type='text/javascript'>alert('请输入姓名');window.history.back();</script>";
                exit();
            }

            if(!$data['sex']){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo "<script type='text/javascript'>alert('请选择客户性别');window.history.back();</script>";
                exit();
            }

            //if(!$data['age']){
            //    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
            //    echo "<script type='text/javascript'>alert('请输入年龄');window.history.back();</script>";
            //    exit();
            //}






            if(!$id){
                $complate=getcomplatestatus();
                if($data['memberstatus']==$complate){
                    $data['complatetime']=date("Y-m-d H:i:s");
                }
                $data['applystatus']=2;
                $data['staffid']=$manageid;
                $data['creater']=$manageid;
                $alter=M('member')->data($data)->add();

                $datarec=array();
                $datarec['staffid']=$manageid;
                $datarec['memberid']=$alter;
                $datarec['addtime']=date('Y-m-d H:i:s');
                $datarec['updatetime']=date('Y-m-d H:i:s');
                $datarec['status']=1;
                $addrec=M('member_apply')->data($datarec)->add();


            }else{

                $find=M ( 'member' )->where ( array (
                    'id' => $id
                ) )->getField("complatetime");
                if(!$find){
                    $complate=getcomplatestatus();
                    if($data['memberstatus']==$complate){
                        $data['complatetime']=date("Y-m-d H:i:s");
                    }
                }

                $alter=M('member')->where(array('id'=>$id))->data($data)->save();
            }

            if($alter===false){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo "<script type='text/javascript'>alert('修改出错，请稍后重试');window.history.back();</script>";
                exit();
            }else{
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                if($id){
                    echo "<script type='text/javascript'>alert('修改成功');window.location.href='/Crm/Custom/detail/id/{$id}.html';</script>";
                }else{
                    echo "<script type='text/javascript'>alert('新增成功');window.location.href='/Crm/Custom/index/act/my.html';</script>";
                }

                exit();
            }

        }


        $member=M('member')->where(array('id'=>$id))->find();
        $extendinfo=json_decode($member['extendinfo'],true);
        $this->assign('extendinfo',$extendinfo);
        $this->assign('member',$member);


        //客户来源
        $source=M('member_source')->where(array('status'=>1))->select();
        $this->assign('source',$source);

        //客户等级
        $level=M('member_level')->where(array('status'=>1))->select();
        $this->assign('level',$level);

        //产品类别
        $producttype=M('member_producttype')->where(array('status'=>1))->select();
        $this->assign('producttype',$producttype);

        //客户状态
        $memberstatus=M('member_status')->where(array('status'=>1))->select();
        $this->assign('memberstatus',$memberstatus);

        ////房源字典列表
        //$list=M('house_dic')->where(array('pid'=>0,'status'=>1))->select();
        //foreach($list as $key =>$val){
        //    $down=M('house_dic')->where(array('pid'=>$val['id'],'status'=>1))->select();
        //    $list[$key]['down']=$down;
        //}
        //$this->assign('housedic',$list);

        //跟进人
        $followman=M('staff')->where(array('id'=>$manageid))->getField('name');
        $this->assign('followman',$followman);
        if($id){
            $this->assign('title','修改'.$member['company']);
        }else{
            $this->assign('title','新增客户');
        }


        $this->display();
    }


    public function addfollowrecord($memberid='',$fromurl="",$type=""){

//        $mpid = get_mp_str();
//        $signPackage = getShareSign ( $mpid );
//        $this->assign ( 'signPackage', $signPackage );
        if($fromurl){
            $this->assign('fromurl',think_decrypt($fromurl));
        }
        $this->assign('type',$type);
        $act=getfollowtype($type);

        $this->assign('act',$act);
        $this->assign('memberid',$memberid);
        $this->assign('title','添加'.$act.'记录');
        $this->display();
    }



    public function getwximg($media_id=''){

        $this->tokenid = get_mp_str ();
        $this->wechatObj = get_wechat_obj ( $this->tokenid );
        $mediaResult = $this->wechatObj->getMediaNew($media_id);


        if ($mediaResult) {
            $path = generateLocalPath('jpg');

            $result = $this->saveWeixinFile($path, $mediaResult);
            $path = ltrim($path, '.');

            if ($result) {
                $this->ajaxReturn(array('info' => $path));
            }

            $this->error('图片写入失败！路径:' . $path);
        }
        $this->error('抓取失败！');
    }


    public function saveWeixinFile($path, $filecontent)
    {
        if (!is_dir(dirname($path)))
            mkdir(dirname($path) . '/', 0777, TRUE);
        return file_put_contents($path, $filecontent);
    }

    public function subrecord(){
        $data=$_POST;
        $type=$data['type'];
        $act=getfollowtype($type);
        $data['staffid']=get_manageid();
        $result=array();
        if(isN($data['address'])){
            $result['status']=0;
            $result['info']='位置获取失败';
            $this->ajaxReturn($result);
        }

        if($type==1){
            if(isN($data['method'])){
                $result['status']=0;
                $result['info']='请选择跟进方式';
                $this->ajaxReturn($result);
            }
            if($data['method']==2){
                if(isN($data['wechat'])){
                    $result['status']=0;
                    $result['info']='请填写客户微信号';
                    $this->ajaxReturn($result);
                }

            }
        }

        if(isN($data['remark'])){
            $result['status']=0;
            $result['info']='请输入'.$act.'详细';
            $this->ajaxReturn($result);
        }

        $data['addtime']=date('Y-m-d H:i:s');
        $data['images']=$data['photo'];
        unset($data['photo']);
        $add=M('staff_follow')->data($data)->add();
        if($add===false){
            $result['status']=0;
            $result['info']=$act.'记录保存出错，请稍后再试';
            $this->ajaxReturn($result);
        }
        $setfield=array();
        $setfield['lastfollowtime']=date('Y-m-d H:i:s');
        if($data['wechat']){
            $setfield['wechat']=$data['wechat'];
        }


        M('member')->where(array('id'=>$data['memberid']))->setField($setfield);

        $result['status']=1;
        $result['info']=$act.'记录保存成功';
        $this->ajaxReturn($result);

    }

    //取消私有
    public function settopublic(){
        $id=$_POST['id'];
        $result=array();
        $set=M('member')->where(array('id'=>$id))->setField(array('applystatus'=>0,'staffid'=>0));
        if($set===false){
            $result['status']=0;
            $result['info']='取消失败，请稍后再试。';
            $this->ajaxReturn($result);
        }

        $sets=M('member_apply')->where(array('memberid'=>$id,'staffid'=>get_manageid()))->setField('status',3);
        if($sets===false){
            $result['status']=0;
            $result['info']='取消失败，请稍后再试。';
            $this->ajaxReturn($result);
        }

        $result['status']=1;
        $result['info']='取消成功。';
        $this->ajaxReturn($result);

    }


}