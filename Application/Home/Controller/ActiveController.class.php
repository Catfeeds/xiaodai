<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2016/8/10
 * Time: 17:22
 */

namespace Home\Controller;


//use Think\Controller;

class ActiveController extends AuthbaseController

{

    public function lists(){
        $list=M('content_active')->where(array('status'=>1))->order('sort asc,addtime desc')->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function view($id=''){
        $db=M('content_active')->where(array('id'=>$id))->find();
        if(!$db){
            go_404();
        }
        M('content_active')->where(array('id'=>$id))->setInc('hits');

        $date=date("Y-m-d");
		
        $tip='';
        $cansign=0;
		
        if($date<$db['start']){
            $tip="未开始";
        }
        if($date>=$db['start'] && $date<=$db['end']){
            $tip='报名中';
            $cansign=1;
            $leftdays=diffBetweenTwoDays($date,$db['end']);
        }
        if($date>$db['end']){
            $tip='已结束';
        }

        $imglist=json_decode($db['images']);
        $this->assign('imglist',$imglist);

        $this->assign('tip',$tip);
        $this->assign('cansign',$cansign);
        $this->assign('leftdays',$leftdays);
        $this->assign('db',$db);
        $this->assign('id',$id);

        $this->assign('title',$db['title']);
        $this->display();
    }


    public function getplayerlist($id = 0, $p = 1,$field='',$sort='',$keyword=''){
        $tblname = 'content_active_memberp';
        $where = array ();
        if($id){
            $where ['activeid'] = $id;
        }
        if($keyword){
            $where1['name']=array('like','%'.$keyword.'%');
            $where1['no']=$keyword;
            $where1['_logic']='or';
            $where['_complex']=$where1;
        }

        $where ['status'] = 1;

        if($sort){
            $order=$field.' '.$sort;
        }else{
            $order='addtime asc';
        }

        $row = C ( 'VAR_PAGESIZE' );
        $list = M ( $tblname )->where ( $where )->page ( $p, $row )->order($order)->select ();
        $this->assign ( 'list', $list );
        $this->assign ( 'p', $p );
        $this->display ( 'getplayerlist' );
    }

    public function sign($id){



        $db=M('content_active')->where(array('id'=>$id))->find();
        if(!$db){
            go_404();
        }
        M('content_active')->where(array('id'=>$id))->setInc('hits');

        $date=date("Y-m-d");
        $tip='';
        $cansign=0;
        if($date<$db['start']){
            $tip="未开始";
        }
        if($date>=$db['start'] && $date<=$db['end']){
            $tip='报名中';
            $cansign=1;
            $leftdays=diffBetweenTwoDays($date,$db['end']);
        }
        if($date>$db['end']){
            $tip='已结束';
        }

		
		 //已报名人数
        $signed=M('content_active_member')->where(array('activeid'=>$id))->select();
        $num=count($signed);

        if($db['limit']>0){
            if($num>=$db['limit']){
                $tip='报名人数已满';
            }
        }

		
        $this->assign('tip',$tip);
        $this->assign('cansign',$cansign);
        $this->assign('leftdays',$leftdays);
        $this->assign('db',$db);
        $this->assign('id',$id);

        $this->assign('title','报名【'.$db['title'].'】');
        $this->display();
    }
	
	
	   public function signpic($id){



        $db=M('content_active')->where(array('id'=>$id))->find();
        if(!$db){
            go_404();
        }
        M('content_active')->where(array('id'=>$id))->setInc('hits');

        $date=date("Y-m-d");
        $tip='';
        $cansign=0;
        if($date<$db['start']){
            $tip="未开始";
        }
        if($date>=$db['start'] && $date<=$db['end']){
            $tip='报名中';
            $cansign=1;
            $leftdays=diffBetweenTwoDays($date,$db['end']);
        }
        if($date>$db['end']){
            $tip='已结束';
        }
		
		 //已报名人数
        $signed=M('content_active_memberp')->where(array('activeid'=>$id))->select();
        $num=count($signed);

        if($db['limit']>0){
            if($num>=$db['limit']){
                $tip='报名人数已满';
            }
        }

		

        $this->assign('tip',$tip);
        $this->assign('cansign',$cansign);
        $this->assign('leftdays',$leftdays);
        $this->assign('db',$db);
        $this->assign('id',$id);

        $this->assign('title','报名【'.$db['title'].'】');
        $this->display();
    }
	

    public function signsuccess(){
        $this->assign('title','报名成功');
        $this->display();
    }

    public function signup(){

        if($_POST){

            $result=array();

            $data=$_POST;
            if(!is_mobile($data['telephone'])){
                $result['status']=0;
                $result['info']='手机号码格式不正确';
                $this->ajaxReturn($result);
            }
            $memberid=get_memberid();
//            $memberid=188;
            $member=M('member')->where(array('id'=>$memberid))->find();
            $active=M('content_active')->where(array('id'=>$data['id']))->find();
			
			
			
            //已报名人数
            $signed=M('content_active_member')->where(array('activeid'=>$data['id']))->select();
            $num=count($signed);

            if($active['limit']>0){
                if($num>=$active['limit']){
                    $result['status']=0;
                    $result['info']='报名人数已满，等待下次报名吧。';
                    $this->ajaxReturn($result);
                }
            }
			
			
            unset($data['id']);
            $data['activeid']=$active['id'];
            $data['activename']=$active['title'];
            $data['no']=get_order_no();
            $data['memberid']=$memberid;
            $data['nickname']=$member['nickname'];
            $data['headimg']=$member['headimgurl'];
            $data['status']=0;
            $data['addtime']=date('Y-m-d H:i:s');
            $data['amount']=$active['price'];
			if( $data['amount']<=0){
				$data['status']=1;
			}

//            $exist=M('content_active_member')->where(array('activeid'=>$active['id'],'memberid'=>$memberid))->find();
//            if($exist){
//                $result['status']=0;
//                $result['info']='您已经报名参加了该活动，请在会员中心-我的活动中查看';
//                $this->ajaxReturn($result);
//            }
            $save=M('content_active_member')->data($data)->add();
            if($save!==false){
                $result['status']=1;
                $result['info']=$data['no'];
                $this->ajaxReturn($result);
            }else{
                $result['status']=0;
                $result['info']='报名失败，请稍后再尝试报名';
                $this->ajaxReturn($result);
            }
        }

    }


    public function signuppic(){
        if($_POST){

            $result=array();

            $data=$_POST;

            $active=M('content_active')->where(array('id'=>$data['activeid']))->find();
            if(!is_mobile($data['mobile'])){
                $result['status']=0;
                $result['info']='手机号码格式不正确';
                $this->ajaxReturn($result);
            }
			
			
			
			  //已报名人数
            $signed=M('content_active_memberp')->where(array('activeid'=>$data['activeid']))->select();
            $num=count($signed);

            if($active['limit']>0){
                if($num>=$active['limit']){
                    $result['status']=0;
                    $result['info']='报名人数已满，等待下次报名吧。';
                    $this->ajaxReturn($result);
                }
            }
			
			
            $data['photo']=substr($data['photo'],0,strlen($data['photo'])-1);


            $photos=explode(',',$data['photo']);

            $data['headimg']=$photos[0];//count($photos)-1
            $data['status']=0;
            if( $active['price']<=0){
                $data['status']=1;
            }

            $data['orderno']=get_order_no();
            $data['memberid']=get_memberid();
            $data['addtime']=date('Y-m-d H:i:s');

//            $exist=M('content_active_memberp')->where(array('activeid'=>$data['activeid'],'memberid'=>get_memberid()))->find();
//            if($exist){
//                $result['status']=0;
//                $result['info']='您已经报名参加了该活动，请不要重复报名';
//                $this->ajaxReturn($result);
//            }

            $save=M('content_active_memberp')->data($data)->add();
            if($save!==false){
                $num=M('content_active_memberp')->where(array('activeid'=>$data['activeid']))->field('max(no) as max')->select();
                $max=$num[0]['max'];
                M ( 'content_active_memberp' )->where ( array (
                    'id' => $save
                ) )->setField ( 'no', $max+1 );


                $memberid=get_memberid();
//            $memberid=188;
                $member=M('member')->where(array('id'=>$memberid))->find();
                $datapay=array();
                $datapay['activeid']=$active['id'];
                $datapay['activename']=$active['title'];
                $datapay['no']= $data['orderno'];
                $datapay['memberid']=$memberid;
                $datapay['nickname']=$member['nickname'];
                $datapay['headimg']=$member['headimgurl'];
                $datapay['status']=0;
                if( $active['price']<=0){
                    $data['status']=1;
                }
                $datapay['addtime']=date('Y-m-d H:i:s');
                $datapay['amount']=$active['price'];
                $save=M('content_active_member')->data($datapay)->add();


                $result['status']=1;
                $result['info']=$datapay['no'];
                $this->ajaxReturn($result);
            }else{
                $result['status']=0;
                $result['info']='报名失败，请稍后再尝试报名';
                $this->ajaxReturn($result);
            }
        }
    }


    public function player($id=''){
        $player=M('content_active_memberp')->where(array('id'=>$id))->find();
        $player['photo']=explode(',',$player['photo']);
        $this->assign('player',$player);
        $this->assign('id',$id);
        $this->display();
    }


    public function vote($id=''){
        $result=array();
        //用户每天最大投票次数
        $voteday=C('config.VOTE_DAY');
        //用户每天对一个选手投票总数
        $voteplayer=C('config.VOTE_PLAYER');
		
        $voteid=get_memberid();
        $date=date('Y-m-d');

		
        //当日总投票次数
        $numall=M('content_active_vote')->where(array('voteid'=>$voteid,'votetime'=>$date))->field('sum(times) as numall')->select();
        $numall=$numall[0]['numall']?$numall[0]['numall']:0;


        if($numall>=$voteday){
            $result['status']=0;
            $result['info']='您已经达到每天的投票最大值，请明天再来投票吧';
            $this->ajaxReturn($result);
        }

        //当日给选手投票次数
        $numplayer=M('content_active_vote')->where(array('playerid'=>$id,'votetime'=>$date,'voteid'=>$voteid))->getField('times');
        $numplayer=$numplayer?$numplayer:0;
        if($numplayer>=$voteplayer){
            $result['status']=0;
            $result['info']='您对该选手的投票数已经达到每天的最大值，请明天再来投票吧';
            $this->ajaxReturn($result);
        }



        if($numplayer){
            $add=M('content_active_vote')->where(array('playerid'=>$id,'votetime'=>$date,'voteid'=>$voteid))->setField('times',array('exp','times + 1'));
        }else{
            $add=M('content_active_vote')->data(array('playerid'=>$id,'votetime'=>$date,'voteid'=>$voteid,'times'=>array('exp','times + 1')))->add();
        }

        if($add!==false){
            M('content_active_memberp')->where(array('id'=>$id))->data(array('tickets'=>array('exp','tickets+1')))->save();
            $result['status']=1;
            $result['info']='投票成功';
            $this->ajaxReturn($result);
        }else{
            $result['status']=0;
            $result['info']='投票失败，请稍后再试';
            $this->ajaxReturn($result);
        }

    }
}