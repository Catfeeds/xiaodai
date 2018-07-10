<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/1/6
 * Time: 13:09
 */

namespace Admin\Controller;


class FinanceController extends BaseController
{

    public function order($status='', $timefrom = '', $timeto = '', $p = 1){

        $where = array ();

//        if (! is_date ( $timefrom )) {
//            $timefrom = date ( 'Y-01-01', NOW_TIME );
//        }
//        if (! is_date ( $timeto )) {
//            $timeto = date ( 'Y-m-d', NOW_TIME );
//        }

        if(!isN($timefrom)){
            $where['addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['addtime']=array('between',array($timefrom,$timeto));
        }

        if(!isN($status)){
            $where['status']=$status;
            $this->assign('status',$status);
        }


        // 表名
        $tblname = 'order';
        $name = '订单';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'amount desc, id asc' )->page ( $p, $row );
        $list = $rs->select ();

        $total=0;
        foreach($list as $key=>$val){
            $total+=$val['amount'];
        }
        $this->assign('total',$total);

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'order';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '订单统计' );
        $this->display ();

    }



    public function member( $timefrom = '', $timeto = '', $p = 1){

        $where = array ();


        if(!isN($timefrom)){
            $where['mo.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['mo.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['mo.addtime']=array('between',array($timefrom,$timeto));
        }


        // 表名
        $tblname = 'order as mo';
        $name = '会员';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $fields="sum(mo.amount) as amount,sum(mo.num) as num,mm.username";
        $rs = M ( $tblname )->join('my_member as mm on mm.id=mo.memberid')->where ( $where )->field($fields)->group('mo.memberid')->order('amount desc')->page ( $p, $row );
        $list = $rs->select ();

        $total=0;
        $allnum=0;
        foreach($list as $key=>$val){
            $total+=$val['amount'];
            $allnum+=$val['num'];
        }
        $this->assign('total',$total);
        $this->assign('allnum',$allnum);



        $this->assign ( "contentlist", $list );
        $count =M ( $tblname )->join('my_member as mm on mm.id=mo.memberid')->where ( $where )->field($fields)->group('mo.memberid')->order('amount desc')->select();
        $count=count($count);
        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'order';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '会员统计' );
        $this->display ();

    }


    public function product( $timefrom = '', $timeto = '', $p = 1){

        $where = array ();


        if(!isN($timefrom)){
            $where['mo.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['mo.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['mo.addtime']=array('between',array($timefrom,$timeto));
        }


        // 表名
        $tblname = 'order_detail as mo';
        $name = '单品';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $fields="sum(mo.num) as num,mcp.title,mcp.price";
        $rs = M ( $tblname )->join('my_content_product as mcp on mcp.id=mo.productid')->where ( $where )->field($fields)->group('mo.productid')->page ( $p, $row );
        $list = $rs->select ();

        $total=0;
        $allnum=0;

        foreach($list as $key=>$val){
            $list[$key]['amount']=$val['num']*$val['price'];
            $total+=$val['num']*$val['price'];
            $allnum+=$val['num'];
        }

        $list=list_sort_by($list,'amount','desc');

        $this->assign('total',$total);
        $this->assign('allnum',$allnum);

        $this->assign ( "contentlist", $list );
        $countlist=M ( $tblname )->join('my_content_product as mcp on mcp.id=mo.productid')->where ( $where )->field($fields)->group('mo.productid')->select();
        $count = count($countlist);

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'order';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '单品统计' );
        $this->display ();

    }


    public function balance( $timefrom = '', $timeto = '', $p = 1){

        $where = array ();


        if(!isN($timefrom)){
            $where['addtime']=array('gt',$timefrom);

        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['addtime']=array('between',array($timefrom,$timeto));
        }


        // 表名
        $tblname = 'account_log';
        $name = '佣金';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $fields="sum(amount) as amount,memberid";
        $rs = M ( $tblname )->where ( $where )->field($fields)->group('memberid')->order('amount desc')->page ( $p, $row );
        $list = $rs->select ();

        foreach($list as $key=>$val){
            $where['memberid']=$val['memberid'];
            $point=M('point_log')->where($where)->getField('sum(point)');
            $list[$key]['point']=$point;
            $username=M('member')->where(array('id'=>$val['memberid']))->getField('username');

            $list[$key]['username']=$username;
        }


        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'order';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '佣金统计' );
        $this->display ();

    }
	
	
	
    public function articleanas(){


        $this->assign('title','文章分析');
        $this->display();
    }

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
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
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



    public function articlemember($id='',$p=1){

        $db=M('content_news')->where(array('id'=>$id))->find();
        $this->assign('db',$db);

        $row = 10;
        $fields="sum(av.num) as sprednum,av.frommemberid,mm.nickname,mm.telephone,mm.headimgurl";
        $members=M('article_view as av')->join('my_member as mm on mm.id=av.frommemberid')->where(array('av.frommemberid'=>array('gt',0),'av.articleid'=>$id))->field($fields)->order('sprednum desc')->group('frommemberid')->page ( $p, $row )->select();

        foreach($members as $km=>$vm){
            //阅读数
            $reads=M('article_view')->where(array('memberid'=>$vm['frommemberid'],'articleid'=>$id))->select();
            //分享数
            $shares=M('article_share')->where(array('memberid'=>$vm['frommemberid'],'articleid'=>$id))->select();

            $members[$km]['readsnum']=count($reads);
            $members[$km]['sharenum']=count($shares);

        }


        $count =M('article_view as av')->join('my_member as mm on mm.id=av.frommemberid')->where(array('av.frommemberid'=>array('gt',0),'av.articleid'=>$id))->field($fields)->order('sprednum desc')->group('frommemberid')->select();
        $count=count($count);

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign('p',$p);
        $this->assign('members',$members);

        $this->display();

    }

    public function memberanas($p=1,$order="",$keyword=''){


        $row = 10;
        $where=array();
        if(!isN($keyword)){
            $where['nickname']=array('like','%'.$keyword.'%');
        }
        $this->assign('keyword',$keyword);

        $memberlist=M('member')->where($where)->field("id,nickname,telephone")->select();//->page ( $p, $row )

        foreach($memberlist as $ml=>$vml){
            //阅读数
            $reads=M('article_view')->where(array('memberid'=>$vml['id']))->select();
            //分享数
            $shares=M('article_share')->where(array('memberid'=>$vml['id']))->select();
            //传播数
            $spred=M('article_view')->where(array('frommemberid'=>$vml['id']))->select();
            //点赞数
            $praise=M('article_praise')->where(array('memberid'=>$vml['id']))->select();

            $memberlist[$ml]['reads']=count($reads);
            $memberlist[$ml]['shares']=count($shares);
            $memberlist[$ml]['spred']=count($spred);
            $memberlist[$ml]['praise']=count($praise);

        }

        if(!$order){
            $order="spred";
        }



//        array_multisort($order,'SORT_DESC',$memberlist);
        $memberlist=arraySequence($memberlist,$order,"SORT_DESC");
        if(count($memberlist)>$row){
            $start=$row*($p-1);
            $end=$row*$p;
            $list=array();
            $k=0;
            for($i=$start;$i<=$end;$i++){
                $list[$k]=$memberlist[$i];
                $k++;
            }

        }else{
            $list=$memberlist;
        }



        $count =M('member')->where($where)->select();
        $count=count($count);

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign('order',$order);
        $this->assign('memberlist',$list);

        $this->display();

    }


    public function memberdetail($id='',$p1=1,$p2=1,$p3=1,$p4=1){
        $member=M('member')->where(array('id'=>$id))->find();
        $this->assign('member',$member);
        $row=5;
        //点赞文章
        $praises=M('article_praise as a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('memberid'=>$id))
            ->field("a.*,mc.title,mc.id as aid")
            ->page($p1,$row)->select();
        $count1 =M('article_praise as a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('memberid'=>$id))
            ->field("a.*,mc.title,mc.id as aid")->select();
        $count1=count($count1);
        if ($count1 > $row) {
            $page1 = new \Think\Page1 ( $count1, $row );
            $page1->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page1', $page1->show () );
        }

        $countpraise=$count1;
        $this->assign('countpraise',$countpraise);

        //分享的文章
        $share=M('article_share a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('memberid'=>$id))->field("sum(a.num) as allnum,a.*,mc.title,mc.id as aid")
            ->group('articleid')->order('allnum desc')->page($p2,$row)->select();

        $count2 =M('article_share a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('memberid'=>$id))->field("sum(a.num) as allnum,a.*,mc.title,mc.id as aid")
            ->group('articleid')->order('allnum desc')->select();
        $countshare=0;
        foreach($count2 as $k2=>$v2){
            $countshare+=$v2['allnum'];
        }
        $this->assign('countshare',$countshare);

        $count2=count($count2);
        if ($count2 > $row) {
            $page2 = new \Think\Page2 ( $count2, $row );
            $page2->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page2', $page2->show () );
        }

        //阅读的文章
        $read=M('article_view a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('memberid'=>$id))->field("sum(a.num) as allnum,a.*,mc.title,mc.id as aid")
            ->group('articleid')->order('allnum desc')->page($p3,$row)->select();
        $count3 =M('article_view a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('memberid'=>$id))->field("sum(a.num) as allnum,a.*,mc.title,mc.id as aid")
            ->group('articleid')->order('allnum desc')->select();

        $countread=0;
        foreach($count3 as $k3=>$v3){
            $countread+=$v3['allnum'];
        }
        $this->assign('countread',$countread);

        $count3=count($count3);
        if ($count3 > $row) {
            $page3 = new \Think\Page3 ( $count3, $row );
            $page3->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page3', $page3->show () );
        }


        //传播影响力
        $spred=M('article_view a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('frommemberid'=>$id))->field("sum(a.num) as allnum,a.*,mc.title,mc.id as aid")
            ->group('articleid')->order('allnum desc')->page($p4,$row)->select();
        $count4 =M('article_view a')
            ->join('my_content_news as mc on mc.id=a.articleid')
            ->where(array('frommemberid'=>$id))->field("sum(a.num) as allnum,a.*,mc.title,mc.id as aid")
            ->group('articleid')->order('allnum desc')->select();

        $countspred=0;
        foreach($count4 as $k4=>$v4){
            $countspred+=$v4['allnum'];
        }
        $this->assign('countspred',$countspred);

        $count4=count($count4);
        if ($count4 > $row) {
            $page4 = new \Think\Page4 ( $count4, $row );
            $page4->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page4', $page4->show () );
        }


        $this->assign('praises',$praises);
        $this->assign('share',$share);
        $this->assign('read',$read);
        $this->assign('spred',$spred);
        $this->assign('title','单个用户分析');
        $this->display();

    }
	
	
    public function articledetail($id="",$name='',$p=1){
        $db=M('content_news')->where(array('id'=>$id))->find();
        $this->assign('db',$db);
        $row=10	;
        switch($name){
            case 'read':
                $list=M('article_view as av')->join('my_member as mm on mm.id=av.memberid')->where(array('articleid'=>$id))->field("av.*,sum(readtime) as allreadtime,mm.nickname,mm.id as mid")->group('av.memberid')->order("addtime desc")->page($p,$row)->select();
                $count=M('article_view as av')->join('my_member as mm on mm.id=av.memberid')->where(array('articleid'=>$id))->field("av.*,sum(readtime) as allreadtime,mm.nickname,mm.id as mid")->group('av.memberid')->order("addtime desc")->select();
                $count=count($count);
                $title="文章阅读详情";
                break;
            case 'share':
                $list=M('article_share as av')->join('my_member as mm on mm.id=av.memberid')->where(array('articleid'=>$id))->field("av.*,sum(av.num) as snum,mm.nickname,mm.id as mid")->order("addtime desc")->group('av.memberid')->page($p,$row)->select();
                $count=M('article_share as av')->join('my_member as mm on mm.id=av.memberid')->where(array('articleid'=>$id))->field("av.*,sum(av.num) as snum,mm.nickname,mm.id as mid")->order("addtime desc")->group('av.memberid')->select();
                $count=count($count);
                $title="文章分享详情";
                break;
            case 'praise':
                $list=M('article_praise as av')->join('my_member as mm on mm.id=av.memberid')->where(array('articleid'=>$id))->field("av.*,mm.nickname,mm.id as mid")->order("addtime desc")->page($p,$row)->select();
                $count=M('article_praise as av')->join('my_member as mm on mm.id=av.memberid')->where(array('articleid'=>$id))->field("av.*,mm.nickname,mm.id as mid")->order("addtime desc")->select();
                $count=count($count);
                $title="文章点赞详情";
                break;
            default:
                break;
        }

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign('id',$id);
        $this->assign('name',$name);
        $this->assign('list',$list);
        $this->assign('title',$title);
        $this->display();

    }



    public function fagnkuan($status='', $timefrom = '', $timeto = '', $p = 1){

        $where = array ();

        if(!isN($timefrom)){
            $where['lr.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['lr.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['lr.addtime']=array('between',array($timefrom,$timeto));
        }


        $where['lr.type']=1;
        // 表名
        $tblname = 'loan_record as lr';
        $name = '本金放款台账';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )
            ->join("my_loan as ml on ml.orderno=lr.orderno")
            ->join('my_member as mm on mm.id=lr.memberid')
            ->field("lr.*,ml.id as mlid,ml.deadline as deadline,ml.interest,ml.damount,mm.username,mm.telephone,mm.idcard")
            ->where ( $where )->order ( 'addtime desc, id asc' )->page ( $p, $row );
        $list = $rs->select ();

        $all=M ( $tblname )
            ->where ( $where )->getField('sum(amount) as allamount');//->select();
        $pagetotal=0;
        $total=$all;
        $status_info = '';
        foreach($list as $key=>&$val){
            //0-待审核，1-已审核，2-已放款，3-已逾期，4-已还款
            switch ($val['status']) {
                case 0:
                    $status_info =  "待审核";
                    break;
                case 1:
                    $status_info =  "已审核";
                    break;
                case 2:
                    $status_info =  "已放款";
                case 3:
                    $status_info =  "已逾期";
                case 4:
                    $status_info =  "已放款";
                case 5:
                    $status_info =  "申请延期";
                    break;
                case 6:
                    $status_info =  "确认延期";
                    break;
            }
            $val['status_info'] = $status_info;
            $pagetotal+=$val['amount'];
        }
//        foreach($all as $ka=>$va){
//            $total+=$va['amount'];
//        }

        $this->assign('pagetotal',$pagetotal);
        $this->assign('total',$total);

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'fagnkuan';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '本金放款台账' );
        $this->display ();

    }
    public function shoukuan($status='', $timefrom = '', $timeto = '', $p = 1){

        $where = array ();

        if(!isN($timefrom)){
            $where['lr.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['lr.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['lr.addtime']=array('between',array($timefrom,$timeto));
        }


        $where['lr.type']=2;
        // 表名
        $tblname = 'loan_record as lr';
        $name = '本金收款台账';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )
            ->join("my_loan as ml on ml.orderno=lr.orderno")
            ->join('my_member as mm on mm.id=lr.memberid')
            ->field("lr.*,ml.id as mlid,ml.deadline as deadline,ml.interest,ml.damount,mm.username,mm.telephone,mm.idcard")
            ->where ( $where )->order ( 'addtime desc, id asc' )->page ( $p, $row );
        $list = $rs->select ();

        $all=M ( $tblname )
            ->where ( $where )->getField('sum(amount) as allamount');//->select();
        $pagetotal=0;
        $total=$all;
        foreach($list as $key=>$val){
            $pagetotal+=$val['amount'];
        }
//        foreach($all as $ka=>$va){
//            $total+=$va['amount'];
//        }

        $this->assign('pagetotal',$pagetotal);
        $this->assign('total',$total);

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'shoukuan';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '本金收款台账' );
        $this->display ();

    }


    public function lixi($status='', $timefrom = '', $timeto = '', $p = 1){

        $where = array ();

        if(!isN($timefrom)){
            $where['lr.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['lr.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['lr.addtime']=array('between',array($timefrom,$timeto));
        }



        // 表名
        $tblname = 'loan_interest as lr';
        $name = '利息台账';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )
            ->join("my_loan as ml on ml.orderno=lr.orderno")
            ->join('my_member as mm on mm.id=lr.memberid')
            ->field("lr.*,ml.id as mlid,ml.damount,mm.username,mm.telephone,mm.idcard")
            ->where ( $where )->order ( 'addtime desc, id asc' )->page ( $p, $row );
        $list = $rs->select ();

        $all=M ( $tblname )
            ->where ( $where )->getField('sum(amount) as allamount');//->select();
        $pagetotal=0;
        $total=$all;
        foreach($list as $key=>$val){
            $pagetotal+=$val['amount'];
        }
//        foreach($all as $ka=>$va){
//            $total+=$va['amount'];
//        }

        $this->assign('pagetotal',$pagetotal);
        $this->assign('total',$total);

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'lixi';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '利息台账' );
        $this->display ();

    }


    public function yuqi($status='', $timefrom = '', $timeto = '', $p = 1){

        $where = array ();

        if(!isN($timefrom)){
            $where['lr.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['lr.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['lr.addtime']=array('between',array($timefrom,$timeto));
        }



        // 表名
        $tblname = 'loan_overdue as lr';
        $name = '逾期收款台账';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )
            ->join("my_loan as ml on ml.orderno=lr.orderno")
            ->join('my_member as mm on mm.id=lr.memberid')
            ->field("lr.*,ml.id as mlid,ml.damount,mm.username,mm.telephone,mm.idcard")
            ->where ( $where )->order ( 'addtime desc, id asc' )->page ( $p, $row );
        $list = $rs->select ();

        $all=M ( $tblname )
            ->where ( $where )->getField('sum(amount) as allamount');//->select();
        $pagetotal=0;
        $total=$all;
        foreach($list as $key=>$val){
            $pagetotal+=$val['amount'];
        }
//        foreach($all as $ka=>$va){
//            $total+=$va['amount'];
//        }

        $this->assign('pagetotal',$pagetotal);
        $this->assign('total',$total);

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "timefrom", $timefrom );
        $this->assign ( "timeto", $timeto );


        // 当前表名
        $control = 'Finance';
        $action = 'yuqi';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'ORDERSTATUS' ) );

        $this->assign ( "title", '逾期收款台账' );
        $this->display ();

    }

}