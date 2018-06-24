<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/12
 * Time: 14:58
 */

namespace Home\Controller;


class LotteryController extends AuthbaseController
{
    public function index($id = '') {
        $lottery = 'lottery';
        if (! $id) {
            $id=get_activity_default_id($lottery);
        }
        // 当前用户信息
        $memberid = get_memberid ();
        $member = get_cache_member ( $memberid, true );
        $this->assign ( 'member', $member );

        // 验证活动
        $check = $this->checkActivity ( $member, $id );
        $this->assign ( 'check', $check );

        // 获取活动信息
        $activity = get_cache_activity ( $lottery, $id );
        $this->assign ( 'activity', $activity );

        // 获取分享信息
        $share = $this->getShareInfo ( $id );
        $this->assign ( 'share', $share );

        // 奖项
        $where = array ();
        $where ['actid'] = $id;
        $where ['status'] = 1;
        $prizes = M ( $lottery . '_prize' )->where ( $where )->order ( 'sort asc' )->select ();//->field ( 'id,title,prize,num,type,amount' )

        $count = count ( $prizes );

        $mod = $count % 3; // 补充的未中奖数
        if ($count < 3) {
            $mod = 6 - $count;
        } else {
            $mod = 3 - $mod;
        }
        $none = array ();
        for($i = 0; $i < $mod; $i ++) {
            $none [] = $this->noneprize ();
        }
        $prizes = array_merge ( $prizes, $none );
        foreach ( $prizes as $k => $v ) {
            if ($k % 2 == 0) {
                $prizes [$k] ['color'] = '#FFF4D6';
            } else {
                $prizes [$k] ['color'] = '#FFFFFF';
            }
        }
        $this->assign ( 'prizes', $prizes );

        // 活动ID
        $this->assign ( 'actid', $id );

        //抽奖次数
        $num = $this->times ( $id, true );
        $this->assign('lotterynum',$num);

        $title = $activity ['title'];
        $this->assign ( 'title', $title );
        $this->display ();
    }

    /**
     * 空奖
     *
     * @param number $id
     */
    private function noneprize($id = 0) {
        $none = array ();
        $none ['id'] = $id;
        $none ['title'] = '谢谢参与';
        $none ['prize'] = '未中奖';
        $none ['type'] = 0;
        $none ['amount'] = 0;
        return $none;
    }

    /**
     * 中奖信息
     */
    public function record($actid = '', $act='',$sn='', $p = 1) {
        $lottery = 'lottery';
        $memberid = get_memberid ();
        if (IS_POST) {
            switch ($act){
                case 'prize':
                    $data=$_POST;
                    if(!($actid)){
                        $this->error('对不起，活动不存在！');
                    }
                    if(isN($sn)){
                        $this->error('对不起，兑奖码不能为空！');
                    }
                    $where=array();
                    $where['actid']=$actid;
                    $find=M($lottery.'_activity')->where($where)->find();
                    if($find){
                        //查找兑奖码
                        $where=array();
                        $where['actid']=$actid;
                        $where['sn']=$sn;
                        $find=M($lottery.'_record')->where($where)->find();
                        if($find){
                            if($find['status']==1){
                                $this->error('对不起，不能重复兑奖！');
                            }else{
                                //销毁
                                $result=get_activity_prize($lottery,$actid,$sn);
                                if($result['status']){
                                    $this->success('恭喜，兑奖成功！');
                                }else{
                                    $this->error($result['info']);
                                }
                            }
                        }else{
                            $this->error('对不起，兑奖码不存在！');
                        }
                    }else{
                        $this->error('对不起，活动不存在！');
                    }
                    break;
                case 'sn':
                    //兑奖
                    $data=$_POST;
                    if(!($data['actid'])){
                        $this->error('对不起，活动不存在！');
                    }
                    if(isN($data['sn'])){
                        $this->error('对不起，兑奖码不能为空！');
                    }
                    if(isN($data['pwd'])){
                        $this->error('对不起，商家密码不能为空！');
                    }
                    $where=array();
                    $where['actid']=$actid;
                    $find=M($lottery.'_activity')->where($where)->find();
                    if($find){
                        if($find['pwd']!=trim($data['pwd'])){
                            $this->error('对不起，商家密码错误！');
                        }else{
                            //查找兑奖码
                            $where=array();
                            $where['actid']=$actid;
                            $where['sn']=$data['sn'];
                            $find=M($lottery.'_record')->where($where)->find();
                            if($find){
                                if($find['status']==1){
                                    $this->error('对不起，不能重复兑奖！');
                                }else{
                                    //销毁
                                    //M($lottery.'_record')->where($where)->setField('status',1);
                                    $result=get_activity_prize($lottery,$actid,$data['sn']);
                                    if($result['status']){
                                        $this->success('恭喜，兑奖成功！');
                                    }else{
                                        $this->error($result['info']);
                                    }
                                }
                            }else{
                                $this->error('对不起，兑奖码不存在！');
                            }
                        }
                    }else{
                        $this->error('对不起，活动不存在！');
                    }
                    break;

                case 'info':
                    //完善资料
                    $data=$_POST;
                    if(!($data['actid'])){
                        $this->error('对不起，活动不存在！');
                    }
                    if(isN($data['sn'])){
                        $this->error('对不起，兑奖码不能为空！');
                    }
                    if(isN($data['telephone'])){
                        $this->error('对不起，联系电话不能为空！');
                    }
                    if(isN($data['address'])){
                        $this->error('对不起，联系地址不能为空！');
                    }
                    $where=array();
                    $where['actid']=$actid;
                    $find=M($lottery.'_activity')->where($where)->find();
                    if($find){
                        //查找兑奖码
                        $where=array();
                        $where['actid']=$actid;
                        $where['sn']=$data['sn'];
                        $find=M($lottery.'_record')->where($where)->find();
                        if($find){
                            if($find['info']){
                                $this->error('对不起，不能修改资料！');
                            }else{
                                $info=array();
                                $info['telephone']=$data['telephone'];
                                $info['address']=$data['address'];
                                M($lottery.'_record')->where($where)->setField('info',json_encode($info));
                                $this->success('恭喜，资料提交成功！');
                            }
                        }else{
                            $this->error('对不起，兑奖码不存在！');
                        }
                    }else{
                        $this->error('对不起，活动不存在！');
                    }
                    break;

                default:

                    $where = array ();
                    $where ['actid'] = $actid;
                    $where ['memberid'] = $memberid;
                    $prizes = M ( $lottery . '_record' )->where ( $where )->select ();
                    $this->success ( $prizes );
            }
        } else {

            $tblname = $lottery . '_record';
            $where = array ();
            $where ['a.actid'] = $actid;
            $where ['a.memberid']=$memberid;
            $order = 'a.id desc';
            $row = C ( 'VAR_PAGESIZE' );
            $field = 'a.id,a.memberid,a.sn,a.prize,a.addtime,a.status,a.type,a.info,b.nickname,b.username,b.mobile,b.headimgurl';
            $rs = M ( $tblname . ' as a ' )->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->order ( 'id desc' )->field ( $field )->page ( $p, $row );
            $list = $rs->select ();
            $this->assign ( "list", $list );
            $count = $rs->where ( $where )->count ();
            if ($count > $row) {
                $page = new \Think\Bspage ( $count, $row );
                $page->setConfig ( 'prev', '上一页' );
                $page->setConfig ( 'next', '下一页' );
                $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %DOWN_PAGE% %END%' );
                $this->assign ( 'page', $page->show () );
            }
            $this->assign('prizetypelist',C('PRIZETYPE'));

            $this->assign ( 'actid', $actid );
            $this->assign ( 'memberid', get_memberid() );
            $this->assign ( 'title', '中奖记录' );
            $this->display ();
        }
    }

    /**
     * 活动验证
     */
    public function times($actid = '', $ret = false) {
        $lottery = 'lottery';
        $memberid = get_memberid ();
        $activity = get_cache_activity ( $lottery, $actid );
        $num = get_activity_play_num ( $lottery, $actid, $memberid, $activity ['iseveryday'] );

        // 再加上分享数
        $sharenum = 0;
        if ($activity ['sharenum']) {
            $sharenum = get_activity_share_num ( $lottery, $actid, $memberid, $activity ['iseveryday'] );
            if ($sharenum) {
                $sharenum = $activity ['sharenum'];
            }
        }
        $canplaynum = $activity ['num'] + $sharenum;
        if ($num > $canplaynum) {
            $num = 0;
        } else {
            $num = $canplaynum - $num;
        }
        if ($ret) {
            return intval ( $num );
        } else {
            $this->success ( intval ( $num ) );
        }
    }

    /**
     * 分享
     */
    public function share($url = '', $actid = '') {
        if (IS_POST) {
            if ($actid) {
                $memberid = get_memberid ();
                $lottery = 'lottery';
                $tblname = $lottery . '_share';
                $where = array ();
                $where ['memberid'] = $memberid;
                $where ['actid'] = $actid;
                $where ['date'] = date2format ( NOW_TIME );
                $find = M ( $tblname )->where ( $where )->find ();
                if ($find) {
                    $where ['num'] = $find ['num'] + 1;
                    $save = M ( $tblname )->where ( array (
                        'id' => $find ['id']
                    ) )->data ( $where )->save ();
                } else {
                    $where ['num'] = 1;
                    $save = M ( $tblname )->data ( $where )->add ();
                }
                $this->success ( $save );
            } else {
                $signPackage = getShareSign ( get_mp_str (), $url );
                $signPackage ['debug'] = false;
                $signPackage ['jsApiList'] = array (
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'previewImage'
                );
                $this->success ( $signPackage );
            }
        }
    }

    /**
     * 抽奖
     */
    public function prize($actid = '') {
        if (IS_POST) {
            $result = $this->getPrize ( $actid );
            if ($result ['status']) {
                $prize=$result['info'];
                $ret=array();
                $ret['id']=$prize['id'];
                $ret['amount']=$prize['amount'];
                $ret['prize']=$prize['prize'];
                $ret['type']=$prize['type'];
                $ret['title']=$prize['title'];
                $ret['sn']=$prize['sn'];
                $this->success ( $ret );
            } else {
                $this->error ( $result ['info'] );
            }
        } else {
            exit ();
        }
    }

    /**
     * 抽奖事务
     */
    private function getPrize($actid = 0) {
        if (! $actid) {
            return er ( '活动参数错误' );
        }
        $memberid = get_memberid ();
        $member = get_cache_member ( $memberid );
        $check = $this->checkActivity ( $member, $actid );
        if (! $check ['status']) {
            return er ( $check ['info'] );
        } else {
            // 参与记录
            $this->setHistory ( $memberid, $actid );
        }
        $lottery = 'lottery';
        $where = array ();
        $where ['actid'] = $actid;
        $where ['status'] = 1;
        $prizes = M ( $lottery . '_prize' )->where ( $where )->getField ( 'id,probability', true );
        $sum = array_sum ( $prizes );
        $none = array (
            0 => 100 - $sum
        );
        //$prizes = array_merge ( $none, $prizes );
        $prizes[0]=(100-$sum);
        $lotteryid = get_rand ( $prizes ); // 中奖ID
        if ($lotteryid) {
            // 没有中奖机会
            $activity = get_cache_activity ( $lottery, $actid );
            $times = get_activity_lottery_num ( $lottery, $actid, $memberid, $activity ['iseveryday'] );
            if (! ($times < $activity ['times'])) {
                return er ( '未中奖' );
            }

            // 奖品剩余数量
            $where1 = array ();
            $where1 ['id'] = $lotteryid;
            $prize = M ( $lottery . '_prize' )->where ( $where1 )->find ();
            if ($prize) {
                $stock = $prize ['num'] - $prize ['getnum'];
                if (! ($stock > 0)) {
                    return er ( '奖品已抽完' );
                } else {
                    $model = M ( $lottery . '_prize' );
                    $model->startTrans ();
                    $data = array ();
                    $data ['getnum'] = $prize ['getnum'] + 1;
                    $save = M ( $lottery . '_prize' )->where ( $where1 )->data ( $data )->save ();
                    if ($save) {
                        // 写中奖记录
                        $data = array ();
                        $data ['memberid'] = $memberid;
                        $data ['actid'] = $actid;
                        $data ['sn'] = rand_code ( $member );
                        $data ['prize'] = json_encode ( $prize );
                        $data ['status'] = 0;
                        $data ['date'] = date2format ( NOW_TIME );
                        $data ['type'] = $prize['type'];
                        $recordid = M ( $lottery . '_record' )->data ( $data )->add ();
                        if ($recordid) {
                            $model->commit ();
                            $prize['sn']=$data['sn'];
                            return ok ( $prize  );
                        } else {
                            $model->rollback ();
                            return er ( '未中奖' );
                        }
                    } else {
                        $model->rollback ();
                        return er ( '未中奖' );
                    }
                }
            } else {
                return er ( '奖品不存在' );
            }
        } else {
            return (er ( '未中奖' ));
        }
    }

    /**
     * 写抽奖记录
     *
     * @param string $memberid
     * @param string $actid
     */
    private function setHistory($memberid = '', $actid = '') {
        $lottery = 'lottery';
        $tblname = $lottery . '_history';
        $where = array ();
        $where ['memberid'] = $memberid;
        $where ['actid'] = $actid;
        $where ['date'] = date2format ( NOW_TIME );
        $find = M ( $tblname )->where ( $where )->find ();
        if ($find) {
            $where ['num'] = $find ['num'] + 1;
            $save = M ( $tblname )->where ( array (
                'id' => $find ['id']
            ) )->data ( $where )->save ();
        } else {
            $where ['num'] = 1;
            $save = M ( $tblname )->data ( $where )->add ();
        }
        return $save;
    }

    /**
     * 活动验证
     */
    private function checkActivity($member = array(), $actid = '') {
        $lottery = 'lottery';
        $activity = get_cache_activity ( $lottery, $actid );
        // 开始日期
        if (!isN ( $activity ['timefrom'] )) {
            if (strtotime ( $activity ['timefrom'] ) > NOW_TIME) {
                return er ( '活动尚未开始' );
            }
        }

        // 结束日期
        if (!isN ( $activity ['timeto'] )) {
            if (strtotime ( $activity ['timeto'] ) < NOW_TIME) {
                return er ( '活动已结束' );
            }
        }

        // 需要关注
        if ($activity ['issubscribe']) {
            if (! $member ['subscribe']) {
                return er ( '请先关注公众号' );
            }
        }

        // 可抽奖次数
        if ($activity ['num']) {
            // 每天都可抽奖
            $num = $this->times ( $actid, true );
            // $num = get_activity_play_num ( $lottery, $actid, $member ['id'], $activity ['iseveryday'] );
            if ($num < 1) {
                return er ( '抽奖机会已用完' );
            }
        } else {
            // return er ( '没有抽奖机会' );
        }
        return ok ( '活动可用' );
    }




    /**
     * 获取分享信息
     *
     * @param string $actid
     */
    private function getShareInfo($actid = '') {
        $lottery = 'lottery';
        $activity = get_cache_activity ( $lottery, $actid );
        // sharenum,sharelogo,sharetitle,shareintro,shareurl
        $share = array ();
        $share ['sharetitle'] = $activity ['title'];
        $share ['sharelogo'] = get_resource_url ( $activity ['indexpic'] );
        $share ['shareintro'] = $activity ['remark'];
        $share ['shareurl'] = get_current_url ();
        if ($activity ['sharetitle']) {
            $share ['sharetitle'] = $activity ['sharetitle'];
        }
        if ($activity ['sharelogo']) {
            $share ['sharelogo'] = get_resource_url ( $activity ['sharelogo'] );
        }
        if ($activity ['shareintro']) {
            $share ['shareintro'] = $activity ['shareintro'];
        }
        if (is_url ( $activity ['shareurl'] )) {
            $share ['shareurl'] = $activity ['shareurl'];
        }
        return $share;
    }
}