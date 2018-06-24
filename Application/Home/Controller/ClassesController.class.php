<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2016/12/27
 * Time: 13:15
 */

namespace Home\Controller;

//use Think\Controller;

class ClassesController extends AuthbaseController
{
    public function index($id='',$teacherid='',$companyid=''){
        if($id){
            $id=$id?$id:0;
        }
        if($teacherid){
            $teacher=M('category_teacher')->where(array('id'=>$teacherid))->find();
        }else{
            $teacherid=$teacherid?$teacherid:0;
        }
        if($companyid){
            $company=M('category_company')->where(array('id'=>$companyid))->find();
        }else{
            $companyid=$companyid?$companyid:0;
        }

        //左侧分类
        $categorylist=M('category_classes')->where(array('status'=>1))->order('sort asc')->select();

        $this->assign ( 'categorylist', $categorylist );

        $this->assign ( 'categoryid', $id );
        $this->assign ( 'teacherid', $teacherid );
        $this->assign ( 'companyid', $companyid );
        $this->assign('teacher',$teacher['name']?$teacher['name']:'选择老师');
        $this->assign('company',$company['name']?$company['name']:'选择单位');
        $this->assign('fromurl',think_encrypt(get_current_url()));
        $this->assign ( 'title', '课程列表' );
        $this->assign ( 'keywords','课程列表' );
        $this->assign ( 'description', '课程列表' );

        $this->display ();

    }

    public function getclassList($id='',$teacherid='',$companyid='',$p=1){
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $where = array ();
        $where ['memberid'] = get_memberid ();
        if($id){
            $where['sortpath']=array('like','%,'.$id.',%');
        }
        if($teacherid){
            $where['teacherid']=$teacherid;
        }
        if($companyid){
            $where['companyid']=$companyid;
        }


        $list = M ( "content_classes" )->where ( $where )->order ( 'id desc' )->page ( $p, $row )->select ();


        $this->assign ( "list", $list );
        $this->assign ( 'p', $p );
        $this->display ();
    }

    public function company($fromurl='',$keyword=''){

        if(IS_POST){
            $keyword=$_POST['keyword'];
        }
        $fromurl=selecturl($fromurl,'companyid');

        $where=array();
        if($keyword){
            $where['name']=array('like','%'.$keyword.'%');
        }
        $list=M('category_company')->where($where)->select();
        $this->assign('list',$list);
        $this->assign('keyword',$keyword);
        $this->assign('fromurl',$fromurl);
        $this->display();
    }

    public function teacher($fromurl='',$keyword=''){

        if(IS_POST){
            $keyword=$_POST['keyword'];
        }
        $fromurl=selecturl($fromurl,'teacherid');
        $where=array();
        if($keyword){
            $where['name']=array('like','%'.$keyword.'%');
        }
        $list=M('category_teacher')->where($where)->select();
        $this->assign('list',$list);
        $this->assign('keyword',$keyword);
        $this->assign('fromurl',$fromurl);
        $this->display();
    }

    public function view($id=''){
        $find=M('content_classes')->where(array('id'=>$id))->find();
        if(!$find){
            go_404();
        }

        M('content_classes')->where(array('id'=>$id))->setInc('sold',1);

         if($find['price']>0){
            $memberid=get_memberid();
            //如果是视频课程，则查询该会员等级，该视频是否对该等级会员免费
            if($find['type']==2){
                $memberpid=M('member')->where(array('id'=>$memberid))->getField('pid');
                $memberlevel=M('category_member')->where(array('id'=>$memberpid))->find();
                $permission=explode(',',$memberlevel['praviteper']);
                if(in_array($id,$permission)){
                    $ispay=1;
                }else{
                    $ispay=M('class_order')->where(array('memberid'=>$memberid,'classid'=>$id,'status'=>1))->find();
                }
            }else{
                $ispay=M('class_order')->where(array('memberid'=>$memberid,'classid'=>$id,'status'=>1))->find();
            }
        }else{
            $ispay=1;
        }

		
        $ques=M('wechat_mp')->where(array('id'=>1))->getField('quesjson');
        $ques=json_decode($ques,true);
        $i=rand(0,count($ques));
//        dump($i);die;
        $this->assign('ques',$ques[$i]['ques']);
        $this->assign('answ',$ques[$i]['answ']);

		 $this->assign('fromurl',think_encrypt(get_current_url()));
        $this->assign('ispay',$ispay);
        $this->assign('find',$find);

        $this->assign('title',$find['title']);
        $this->display();
    }
	
	
	
    public function question($fromurl=""){
        $ques=M('wechat_mp')->where(array('id'=>1))->getField('quesjson');
        $ques=json_decode($ques,true);
        $i=rand(0,count($ques));
//        dump($i);die;
        $this->assign('ques',$ques[$i]['ques']);
        $this->assign('answ',$ques[$i]['answ']);
        $this->assign('fromurl',think_decrypt($fromurl));
        $this->assign('title',"问卷");
        $this->display();
    }

    public function pay(){
        $id=$_POST['id'];
        $memberid=get_memberid();

        $find=M('class_order')->where(array('memberid'=>$memberid,'classid'=>$id))->find();
        $result=array();
        if($find){
            $result['status']=1;
            $result['info']=$find['orderno'];
            $this->ajaxReturn($result);
        }else{
            $class=M('content_classes')->where(array('id'=>$id))->find();
            $data['orderno']=get_order_no();
            $data['memberid']=$memberid;
            $data['classid']=$id;
            $data['classname']=$class['title'];
            $data['amount']=$class['price'];
            $data['status']=0;
            $data['addtiime']=date("Y-m-d H:i:s");
            $add=M('class_order')->data($data)->add();
            if($add===false){
                $result['status']=0;
                $result['info']="生成支付订单失败，请稍后再试";
                $this->ajaxReturn($result);
            }
            $result['status']=1;
            $result['info']=$data['orderno'];
            $this->ajaxReturn($result);
        }





    }

    public function mainresume($companyid=''){
        //推荐单位
        $company=M('category_company')->where(array('isresume'=>1))->limit(6)->order('sort asc')->select();
        $this->assign('company',$company);

        //热门课程
        $where=array();
        if($companyid){
            $where['companyid']=$companyid;
        }

        $where['isresume']=1;
        $class=M('content_classes')->where($where)->limit(8)->order('sort asc, sold desc')->select();
        $this->assign('class',$class);


        $url=get_current_domain().'/Classes/index';
        $url=think_encrypt($url);
        $this->assign('url',$url);
        $this->assign('title','推荐课程');
        $this->display();
    }

    public function signup($id){
        $find=M('content_signup')->where(array('id'=>$id))->find();

        if(!$find){
            $this->signuperr(think_encrypt('课程已失效'));
        }

        $memberid=get_memberid();

        $signed=M('content_signup_record')->where(array('memberid'=>$memberid,'signupid'=>$id))->find();

        if($signed){
            $this->signuperr(think_encrypt('您已经签到过了'));
            die;
        }
        $this->assign('id',$id);
        $this->assign('find',$find);
        $this->display();

    }

    public function getsignup(){
        $data=$_POST;
        $result=array();
        $distance=getDistance($data['lat'],$data['long'],$data['tolat'],$data['tolong']);
        $scope=C('config.SIGNUOP_SCOPE')?C('config.SIGNUOP_SCOPE'):0;
        if($distance>$scope){
            $result['status']=0;
            $result['info']=think_encrypt('当前位置距离签到地址大于'.$scope.'米');
            $this->ajaxReturn($result);
        }
        
        $find=M('content_signup_record')->where(array('memberid'=>get_memberid(),'signupid'=>$data['id']))->find();
        if($find){
            $result['status']=0;
            $result['info']=think_encrypt('您已经签到过了');
            $this->ajaxReturn($result);
        }

        $datas['signupid']=$data['id'];
        $datas['memberid']=get_memberid();
        $datas['addtime']=date('Y-m-d H:i:s');
        $save=M('content_signup_record')->data($datas)->add();
        if($save===false){
            $result['status']=0;
            $result['info']=think_encrypt('添加签到记录失败');
            $this->ajaxReturn($result);
        }
        $result['status']=1;
        $result['info']='签到成功';
        $this->ajaxReturn($result);
    }

    public function signuperr($info){
        $this->assign('info',think_decrypt($info));
        $this->display('signuperr');
    }

    public function signupsucc($id){
        $find=M('content_signup')->where(array('id'=>$id))->find();
        $this->assign('find',$find);
        $this->assign('time',M('content_signup_record')->where(array('signupid'=>$id,'memberid'=>get_memberid()))->getField('addtime'));
        $this->display();
    }
}