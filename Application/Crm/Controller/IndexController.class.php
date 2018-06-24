<?php

namespace Crm\Controller;

class IndexController extends BaseController {
	public function index() {
		$manageid=get_manageid();
		$staff=M('staff')->where(array('id'=>$manageid))->find();
		$this->assign('staff',$staff);

		//客户数
		$cusnum=M('member')->where(array('staffid'=>$manageid))->count();
		$cusnum=$cusnum?$cusnum:0;
		$this->assign('cusnum',$cusnum);

		//带看数
		$seenum=M('staff_follow')->where(array('staffid'=>$manageid,'type'=>2))->count();
		$seenum=$seenum?$seenum:0;
		$this->assign('seenum',$seenum);

		//跟进数
		$follownum=M('staff_follow')->where(array('staffid'=>$manageid,'type'=>1))->count();
		$follownum=$follownum?$follownum:0;
		$this->assign('follownum',$follownum);

		$title=C('config.WEB_SITE_TITLE');
		$this->assign ( 'title', $title.'-CRM系统' );
		$this->display ();
	}

	public function alterinfo(){
		$manageid=get_manageid();
		$db=M('staff')->where(array('id'=>$manageid))->find();
		$this->assign('db',$db);

		if(IS_POST){
			$data=$_POST;
			
			
			if(!isN($data['userpwd'])){
				if($data['userpwd']!=$data['checkpwd']){
					echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					echo "<script type='text/javascript'>alert('两次输入的密码不一致');window.history.back();</script>";
					exit();
				}

				$data['userpwd']=md5($data['userpwd']);
				unset($data['checkpwd']);
			}else{
				unset($data['userpwd'],$data['checkpwd']);
			}
			

			unset($data['id']);

			$save=M('staff')->where(array('id'=>$manageid))->data($data)->save();
			if($save===false){
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script type='text/javascript'>alert('修改出错，请稍后重试');window.history.back();</script>";
				exit();
			}else{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo "<script type='text/javascript'>alert('修改成功');window.location.href='/Crm/Index/index.html';</script>";
				exit();
			}

		}

		$department=M('department')->where(array('pid'=>0,'status'=>1))->select();
		$this->assign('department',$department);

		$this->assign('title','修改资料');
		$this->display();
	}


	/**
	 * 省市县联动
	 *
	 * @param string $tbl        	
	 * @param number $id        	
	 */
	public function getArea($tbl = 'china_province', $id = null) {
		$html = '';
		$html = get_area ( $tbl, $id );
		echo $html;
	}

	public function performance(){
		$manageid=get_manageid();
		$where=array();
		$where['m.staffid']=$manageid;
		$where['o.status']=3;
		$memberlist=M('member')->where(array('staffid'=>$manageid))->select();
		$field="o.*,m.userreal";
		$orderlist=M('order as o')->join('my_member as m on m.id=o.memberid')->field($field)->where($where)->select();
		$wherec=array();
		$wherec['m.staffid']=$manageid;
		$wherec['co.status']=1;
		$fieldc="co.*,m.userreal";
		$classorder=M('class_order as co')->join('my_member as m on m.id=co.memberid')->field($fieldc)->where($wherec)->select();
		$total=0;
		foreach($orderlist as $k=>$v){
			$total+=$v['amount'];
		}
		foreach($classorder as $k=>$v){
			$total+=$v['amount'];
		}

		$this->assign('total',$total);
		$this->assign('orderlist',$orderlist);
		$this->assign('classorder',$classorder);


		$this->assign('title','我的业绩');
		$this->display();
	}


	public function statistics(){

		$this->assign('title','销售漏斗');
		$this->display();
	}

	public function getstatistics(){

		$start=$_POST['start'];
		$end=$_POST['end'];
		$username=$_POST['username'];
		$act=$_POST['act'];//1-个人统计，2-总统计
		$items=array();
		$data=array();
		switch($act){
			case 1:
				$manageid=get_manageid();
				//新增客户
				$where=array();
				$where['staffid']=$manageid;
				$where['status']=1;

				$cate=M('member_status')->where(array('status'=>1))->select();
				foreach($cate as $k=>$v){
					$items[$k]=$v['name'];
					$data[$k]['name']=$v['name'];
					$where['memberstatus']=$v['id'];
					$nums=M('member')->where($where)->count();
					$nums=$nums?$nums:0;
					$data[$k]['value']=$nums;
				}
				break;
			case 2:
				//新增客户
				$where=array();
				$where['staffid']=array('lt',1);
				$where['status']=1;

				$cate=M('member_status')->where(array('status'=>1))->select();
				foreach($cate as $k=>$v){
					$items[$k]=$v['name'];
					$data[$k]['name']=$v['name'];
					$where['memberstatus']=$v['id'];
					$nums=M('member')->where($where)->count();
					$nums=$nums?$nums:0;
					$data[$k]['value']=$nums;
				}

				break;
			default:
				break;
		}


		$this->ajaxReturn(array('items'=>$items,'data'=>$data));


	}

	public function fenxitongji(){


		$this->assign('title','智能统计分析');
		$this->display();
	}

	public function baobiao(){
		//客户总数
		$cusnum=M('member')->where(array( 'telephone'=>array('neq','')))->count();

		//合同总数
		$hetongnum=M('contract')->count();

		//项目总数
		$itemnum=M('item')->count();

		//成交客户数
		$comstatus=getcomplatestatus();
		$compnum=M('member')->where(array( 'telephone'=>array('neq',''),'memberstatus'=>array('egt',$comstatus)))->count();

		//客户拥有数排行前6名
		$cusrank=M('member as m')->join('my_staff as s on m.staffid=s.id')
				->where(array( 'm.telephone'=>array('neq','')))
				->field('count(1) as sum,m.staffid,s.name')->group('m.staffid')->limit(6)->order('sum desc')->select();

		//合同拥有数排行前6名
		$contractrank=M('contract as c')->join('my_staff as s on c.masterid=s.id')
				->field('count(1) as sum,c.masterid,s.name')->group('c.masterid')->limit(6)->order('sum desc')->select();

		//合同金额排行前6名
		$conamountrank=M('contract as c')->join('my_staff as s on c.masterid=s.id')
				->field('sum(amount) as sum,c.masterid,s.name')->group('c.masterid')->limit(6)->order('sum desc')->select();

		//项目数排行前6名
		$itemrank=M('item as i')->join('my_staff as s on i.masterid=s.id')
				->where(array('i.status'=>array('neq',2)))
				->field('count(1) as sum,i.masterid,s.name')->group('i.masterid')->limit(6)->order('sum desc')->select();

		//跟进排行前6名
		$followrank=M('staff_follow as f')->join('my_staff as s on f.staffid=s.id')
				->field('count(1) as sum,f.staffid,s.name')->group('f.staffid')->limit(6)->order('sum desc')->select();


		$this->assign('cusnum',$cusnum);
		$this->assign('hetongnum',$hetongnum);
		$this->assign('itemnum',$itemnum);
		$this->assign('compnum',$compnum);
		$this->assign('cusrank',$cusrank);
		$this->assign('contractrank',$contractrank);
		$this->assign('conamountrank',$conamountrank);
		$this->assign('itemrank',$itemrank);
		$this->assign('followrank',$followrank);

		$this->assign('title','智能报表');
		$this->display();
	}

	public function getranklist($type=""){

		$html="";
		switch($type){
			case 'custom':
				//客户拥有数排行前6名
				$where=array();
				$where['m.telephone']=array('neq','');
				$cusrank=M('member as m')->join('my_staff as s on m.staffid=s.id')
						->where($where)
						->field('count(1) as sum,m.staffid,s.name')->group('m.staffid')->order('sum desc')->select();
				foreach($cusrank as $k=>$v){
					$html.="<li data-field=\"custom\" data-id=\"".$v['staffid']."\"><h2 class=\"bh\"><span style=\"line-height: 1.8;\">".($k+1)."、".$v['name']."</span>";
					$html.="<span style=\"float: right;background: #FF5151;color: #fff;padding: 4px 8px;\">".$v['sum']."</span>";

					$html.="<div style=\"position: absolute; top: -5px;left: 0px;\">";
					if($k<=2){
						$html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
					}
					$html.="</div> </h2></li>";

				}
				$title="客户数排行";
				break;
			case 'contract':

				//合同拥有数排行前6名
				$contractrank=M('contract as c')->join('my_staff as s on c.masterid=s.id')
						->field('count(1) as sum,c.masterid,s.name')->group('c.masterid')->order('sum desc')->select();

				foreach($contractrank as $k=>$v){
					$html.="<li data-field=\"contract\" data-id=\"".$v['staffid']."\"><h2 class=\"bh\"><span style=\"line-height: 1.8;\">".($k+1)."、".$v['name']."</span>";
					$html.="<span style=\"float: right;background: #FF5151;color: #fff;padding: 4px 8px;\">".$v['sum']."</span>";

					$html.="<div style=\"position: absolute; top: -5px;left: 0px;\">";
					if($k<=2){
						$html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
					}
					$html.="</div> </h2></li>";
				}
				$title="合同数排行";
				break;
			case 'contractamount':
				//合同金额排行前6名
				$conamountrank=M('contract as c')->join('my_staff as s on c.masterid=s.id')
						->field('sum(amount) as sum,c.masterid,s.name')->group('c.masterid')->order('sum desc')->select();
				foreach($conamountrank as $k=>$v){
					$html.="<li data-field=\"conamountrank\" data-id=\"".$v['staffid']."\"><h2 class=\"bh\"><span style=\"line-height: 1.8;\">".($k+1)."、".$v['name']."</span>";
					$html.="<span style=\"float: right;background: #FF5151;color: #fff;padding: 4px 8px;\">".$v['sum']."</span>";

					$html.="<div style=\"position: absolute; top: -5px;left: 0px;\">";
					if($k<=2){
						$html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
					}
					$html.="</div> </h2></li>";
				}
				$title="合同金额排行";
				break;
			case 'item':
				//项目数排行前6名
				$itemrank=M('item as i')->join('my_staff as s on i.masterid=s.id')
						->where(array('i.status'=>array('neq',2)))
						->field('count(1) as sum,i.masterid,s.name')->group('i.masterid')->order('sum desc')->select();
				foreach($itemrank as $k=>$v){
					$html.="<li data-field=\"item\" data-id=\"".$v['staffid']."\"><h2 class=\"bh\"><span style=\"line-height: 1.8;\">".($k+1)."、".$v['name']."</span>";
					$html.="<span style=\"float: right;background: #FF5151;color: #fff;padding: 4px 8px;\">".$v['sum']."</span>";

					$html.="<div style=\"position: absolute; top: -5px;left: 0px;\">";
					if($k<=2){
						$html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
					}
					$html.="</div> </h2></li>";
				}
				$title="项目数排行";
				break;

			case 'follow':

				//跟进排行前6名
				$followrank=M('staff_follow as f')->join('my_staff as s on f.staffid=s.id')
						->field('count(1) as sum,f.staffid,s.name')->group('f.staffid')->order('sum desc')->select();

				foreach($followrank as $k=>$v){
					$html.="<li data-field=\"follow\" data-id=\"".$v['staffid']."\"><h2 class=\"bh\"><span style=\"line-height: 1.8;\">".($k+1)."、".$v['name']."</span>";
					$html.="<span style=\"float: right;background: #FF5151;color: #fff;padding: 4px 8px;\">".$v['sum']."</span>";

					$html.="<div style=\"position: absolute; top: -5px;left: 0px;\">";
					if($k<=2){
						$html.='<img style="width: 30px;" src="/Public/Admin/stylesheets/images/'.($k+1).'.png" />';
					}
					$html.="</div> </h2></li>";
				}
				$title="跟进数排行";
				break;

		}

		$this->assign('title',$title);
		$this->assign('html',$html);
		$this->display();
	}


	public function showrankdetail($type="",$masterid="",$keyword=""){
		$staff=M('staff')->where(array('id'=>$masterid))->find();
		$this->assign('staff',$staff);
		// 分页
		switch ($type) {
			case 'custom' :
				$where=array();
				$where['staffid']=$masterid;
				$where['telephone']=array('neq','');
				$map=array();
				if($keyword){
					$map['company']=array('like','%'.$keyword.'%');
					$map['userreal']=array('like','%'.$keyword.'%');
					$map['telephone']=array('like','%'.$keyword.'%');
					$map['address']=array('like','%'.$keyword.'%');
					$map['_logic']='or';
					$where['_complex']=$map;
				}

				$rs = M ( 'member' )->where ( $where )->order ( 'id desc' );//->page ( $p, $row );
				$title="客户详细";
				break;
			case 'contract':
			case 'contractamount':
				$where=array();
				$where['masterid']=$masterid;

				$map=array();
				if($keyword){
					$map['name']=array('like','%'.$keyword.'%');
					$map['mastername']=array('like','%'.$keyword.'%');
					$map['no']=array('like','%'.$keyword.'%');
					$map['_logic']='or';
					$where['_complex']=$map;
				}

				$rs = M ( 'contract' )->where ( $where )->order ( 'id desc' );//->page ( $p, $row );
				$title="合同详细";
				break;
			case 'item':
				$where=array();
				$where['status']=array('neq',2);
				$where['masterid']=$masterid;

				$map=array();
				if($keyword){
					$map['name']=array('like','%'.$keyword.'%');
					$map['mastername']=array('like','%'.$keyword.'%');
					$map['_logic']='or';
					$where['_complex']=$map;
				}

				$rs = M ( 'item' )->where ( $where )->order ( 'id desc' );//->page ( $p, $row );
				$title="项目详细";
				break;
			case 'follow':
				$where=array();
				$where['sf.staffid']=$masterid;

				$map=array();
				if($keyword){
					$map['m.userreal']=array('like','%'.$keyword.'%');
					$map['_logic']='or';
					$where['_complex']=$map;
				}
				$field="sf.*,s.name,m.userreal,m.company";
				$rs = M ( 'staff_follow as sf' )
						->join('my_staff as s on s.id=sf.staffid')
						->join('my_member as m on m.id=sf.memberid')
						->where ( $where )->field($field)->order ( 'id desc' );//->page ( $p, $row );
				$title="跟进详细";
				break;

		}
		$list = $rs->select ();

		if($type=='contract' || $type=="contractamount"){
			foreach($list as $k=>$v){
				$now=date('Y-m-d H:i:s');
				$left=diffBetweenTwoDaysreal($now,$v['end']);
				$left=intval($left);
				if($left<=0){
					$list[$k]['left']='已到期';
					$list[$k]['color']='red';
				}else{
					$list[$k]['left']=$left.'天';
					if($left<15){
						$color="orangered";
					}else{
						$color="green";
					}
					$list[$k]['color']=$color;
				}
			}
		}
		$this->assign ( "list", $list );
		$count = $rs->where ( $where )->count ();



//		if ($count > $row) {
//			$page = new \Think\Page ( $count, $row );
//			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
//			$this->assign ( 'page', $page->show () );
//		}
		$this->assign ( "keyword", $keyword );
		$this->assign ( "type", $type );
		$this->assign ( "masterid", $masterid );
		$this->assign ( "title", $title );
		$this->display ();
	}


	public function test(){

		$this->display();
	}


}