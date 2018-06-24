<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/9
 * Time: 17:30
 */

namespace Admin\Controller;


class LotteryController extends BaseController
{
    /**
     * 活动列表，分页
     */
    public function activity($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1,$active='') {
        // 表名
        $tblname = 'lottery_activity';
        $name = '活动';
        if(is_numeric($active)){
            $where=array();
            $where['id']=$pid;
            M($tblname)->where($where)->setField('isdefault',$active);
            if($active){
                $where1=array();
                $where1['id']=array('neq',$pid);
                $where1['active']=1;
                M($tblname)->where($where1)->setField('isdefault',0);
            }
            $this->success($active);
            exit();
        }
        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['title'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['remark'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '2' :
                if (is_number ( $keyword )) {
                    $where ['id'] = $keyword;
                }
                break;
        }

        if (is_numeric ( $pid )) {
            $where ['sortpath'] = array (
                'like',
                '%,' . $pid . ',%'
            );
        }
        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
        $list = $rs->select ();

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        $this->assign ( "searchtype", $searchtype );
        $this->assign ( "status", $status );

        // 当前表名
        $control = 'lottery';
        $action = 'Activity';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '活动列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addActivity() {
        $this->editActivity ();
    }
    /**
     * 添加修改活动
     *
     * @param number $id
     */
    public function editActivity($id = 0) {
        $tblname = 'lottery_activity';
        $name = '活动';
        $tplname = 'editActivity';
        if (IS_POST) {
            $data = $_POST;
            if (isN ( $data ['title'] )) {
                $this->error ( '对不起，' . $name . '标题不能为空！' );
            }

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                // 清除缓存
                get_cache_activity ( 'lottery', $id, true );
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {
                unset ( $data ['id'] );
                $id = M ( $tblname )->data ( $data )->add ();
                M ( $tblname )->where ( array (
                    'id' => $id
                ) )->setField ( 'sort', $id );
                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name );
                // 修改的时候取父ID参数
                $this->assign ( "pid", $db ['pid'] );
            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );

            $control = 'lottery';
            $action = 'Activity';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );
            $this->assign ( "tblname", $tblname );

            $this->display ( $tplname );
        }
    }
    /**
     * 删除活动
     *
     * @param number $id
     */
    public function deleteActivity($id = 0) {
        $tblname = 'lottery_activity';
        $name = '活动';
        $find = M ( $tblname )->find ( $id );
        if ($find) {
            M ( $tblname )->where ( array (
                'id' => $id
            ) )->delete ();
            $this->success ( '恭喜，' . $name . '已删除！' );
        } else {
            $this->error ( '对不起，' . $name . '不存在！' );
        }
    }

    /**
     * 奖项列表，分页
     */
    public function prize($actid = '', $searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['title'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['remark'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '2' :
                if (is_number ( $keyword )) {
                    $where ['id'] = $keyword;
                }
                break;
        }

        if (is_numeric ( $pid )) {
            $where ['sortpath'] = array (
                'like',
                '%,' . $pid . ',%'
            );
        }
        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        if (! is_numeric ( $actid )) {
            $actid = get_current_activity_id ( 'lottery' );
        } else {
            get_current_activity_id ( 'lottery', $actid );
        }
        if($actid){
            $where ['actid'] = $actid;
        }

//		dump($where);die;

        // 表名
        $tblname = 'lottery_prize';
        $name = '奖项';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'sort asc, id desc' )->page ( $p, $row );
        $list = $rs->select ();

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        $this->assign ( "searchtype", $searchtype );
        $this->assign ( "status", $status );

        // 当前表名
        $control = 'lottery';
        $action = 'Prize';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );
        $this->assign ( "prizetypelist", C ( 'PRIZETYPE' ) );

        $actlist = get_cache_list ( 'lottery_activity', '','id,title,sort');

        $this->assign ( "actlist", $actlist );
        $this->assign ( 'actid', $actid );

        $this->assign ( "title", '奖项列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addPrize() {
        $this->editPrize ();
    }
    /**
     * 添加修改奖项
     *
     * @param number $id
     */
    public function editPrize($id = 0) {
        $tblname = 'lottery_prize';
        $name = '奖项';
        $tplname = 'editPrize';
        if (IS_POST) {
            $data = $_POST;
            $actid=get_current_activity_id ( 'lottery' );
            $data ['actid'] = $actid;
            if (isN ( $data ['prize'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }
            if (isN ( $data ['title'] )) {
                $this->error ( '对不起，奖品名称不能为空！' );
            }
            if ($data ['probability'] < 0 || $data ['probability'] > 100) {
                $this->error ( '对不起，中奖概率只能在【0-100】之间！' );
            }
            $where=array();
            $where['actid']=$actid;
            $where['id']=array('neq',$id);
            $probability=M($tblname)->where($where)->sum('probability');
            $maxpro=100-$probability;
            if($data['probability']>$maxpro){
                $this->error('对不起，该奖项概率不能大于'.$maxpro.'%！');
            }
            if ($id) {
                if ($data ['num'] < $data ['getnum']) {
                    $this->error ( '对不起，奖品总数量不能小于已领取数量【' . $data ['getnum'] . '】！' );
                }
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                // 清除缓存
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {
                unset ( $data ['id'] );
                $id = M ( $tblname )->data ( $data )->add ();
                M ( $tblname )->where ( array (
                    'id' => $id
                ) )->setField ( 'sort', $id );
                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name );
                $actid = $db ['actid'];
                // 修改的时候取父ID参数
                $this->assign ( "pid", $db ['pid'] );
            } else {
                // 添加
                $actid = I ( 'actid' );
                $this->assign ( 'title', '添加' . $name );
            }

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "prizetypelist", C ( 'PRIZETYPE' ) );
            $this->assign ( "actid", $actid );
            $this->assign ( "name", $name );

            $control = 'lottery';
            $action = 'Prize';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );
            $this->assign ( "tblname", $tblname );

            $this->display ( $tplname );
        }
    }
    /**
     * 删除奖项
     *
     * @param number $id
     */
    public function deletePrize($id = 0) {
        $tblname = 'lottery_prize';
        $name = '奖项';
        $find = M ( $tblname )->find ( $id );
        if ($find) {
            M ( $tblname )->where ( array (
                'id' => $id
            ) )->delete ();
            $this->success ( '恭喜，' . $name . '已删除！' );
        } else {
            $this->error ( '对不起，' . $name . '不存在！' );
        }
    }

    /**
     * 参与列表，分页
     */
    public function history($actid = '', $searchtype = '', $keyword = '',$timefrom = '', $timeto = '', $p = 1) {
        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['b.nickname'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['b.username'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '2' :
                if (! isN ( $keyword )) {
                    $where ['b.mobile'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
        }
        if (is_date ( $timefrom )) {
            $this->assign ( "timefrom", $timefrom );
            $where ['a.date'] [] = array (
                'egt',
                $timefrom
            );
        }
        if (is_date ( $timeto )) {
            $this->assign ( "timeto", $timeto );
            $timeto = get_date_add ( $timeto, 1 );
            $timeto = date2format ( $timeto );
            $where ['a.date'] [] = array (
                'lt',
                $timeto
            );
        }

        if (! is_numeric ( $actid )) {
            $actid = get_current_activity_id ( 'lottery' );
        } else {
            get_current_activity_id ( 'lottery', $actid );
        }
        $where ['actid'] = $actid;

        // 表名
        $tblname = 'lottery_history';
        $name = '参与';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $field = 'a.id,a.memberid,a.date,a.num,b.nickname,b.username,b.mobile,b.headimgurl';
        $rs = M ( $tblname . ' as a ' )->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->order ( 'id desc' )->field ( $field )->page ( $p, $row );
        $list = $rs->select ();

        $this->assign ( "contentlist", $list );
        $count = $rs->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        $this->assign ( "searchtype", $searchtype );
        $this->assign ( "status", $status );

        // 当前表名
        $control = 'lottery';
        $action = 'History';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        $actlist = get_cache_list ( 'lottery_activity', '', 'id,title' );
        $this->assign ( "actlist", $actlist );
        $this->assign ( 'actid', $actid );

        $this->assign ( "title", '参与列表' );
        $this->display ();
    }


    /**
     * 中奖列表，分页
     */
    public function record($actid = '', $prizeid='', $searchtype = '', $keyword = '',$timefrom = '', $timeto = '', $status='',$p = 1) {
        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['b.nickname'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['b.username'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '2' :
                if (! isN ( $keyword )) {
                    $where ['b.mobile'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '3' :
                if (! isN ( $keyword )) {
                    $where ['a.sn'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
        }
        if (is_date ( $timefrom )) {
            $this->assign ( "timefrom", $timefrom );
            $where ['a.addtime'] [] = array (
                'egt',
                $timefrom
            );
        }
        if (is_date ( $timeto )) {
            $this->assign ( "timeto", $timeto );
            $timeto = get_date_add ( $timeto, 1 );
            $timeto = date2format ( $timeto );
            $where ['a.addtime'] [] = array (
                'lt',
                $timeto
            );
        }
        if($prizeid){
            $where['prize']=array('like','{"id":"'.$prizeid.'"%');
        }
        $this->assign('prizeid',$prizeid);

        if (is_numeric ( $status )) {
            $where ['a.status'] = $status;
        }

        if (! is_numeric ( $actid )) {
            $actid = get_current_activity_id ( 'lottery' );
        } else {
            get_current_activity_id ( 'lottery', $actid );
        }
        $where ['actid'] = $actid;

        // 表名
        $tblname = 'lottery_record';
        $name = '中奖';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $field = 'a.id,a.memberid,a.sn,a.prize,a.addtime,a.status,a.type,a.info,b.nickname,b.username,b.mobile,b.headimgurl';
        $rs = M ( $tblname . ' as a ' )->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->order ( 'id desc' )->field ( $field )->page ( $p, $row );
        $list = $rs->select ();
        $this->assign ( "contentlist", $list );
        $count = $rs->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        $this->assign ( "searchtype", $searchtype );
        $this->assign ( "status", $status );

        // 当前表名
        $control = 'lottery';
        $action = 'Record';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        $this->assign ( "prizetypelist", C ( 'PRIZETYPE' ) );

        //领取状态
        $this->assign('statuslist',C('PRIZESTATUS'));
        $actlist = get_cache_list ( 'lottery_activity', '', 'id,title' );
        $this->assign ( "actlist", $actlist );
        $this->assign ( 'actid', $actid );

        //奖项列表
        $where1=array();
        $where1 ['actid'] = $actid;
        $prizelist = M('lottery_prize')->where($where1)->field('id,title,prize')->cache(true)->order('sort asc')->select();
        $this->assign ( "prizelist", $prizelist );

        $this->assign ( "title", '中奖列表' );
        $this->display ();
    }
    /**
     * 分享列表，分页
     */
    public function share($actid = '', $searchtype = '', $keyword = '',$timefrom = '', $timeto = '', $p = 1) {
        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['b.nickname'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['b.username'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '2' :
                if (! isN ( $keyword )) {
                    $where ['b.mobile'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
        }
        if (is_date ( $timefrom )) {
            $this->assign ( "timefrom", $timefrom );
            $where ['a.date'] [] = array (
                'egt',
                $timefrom
            );
        }
        if (is_date ( $timeto )) {
            $this->assign ( "timeto", $timeto );
            $timeto = get_date_add ( $timeto, 1 );
            $timeto = date2format ( $timeto );
            $where ['a.date'] [] = array (
                'lt',
                $timeto
            );
        }

        if (! is_numeric ( $actid )) {
            $actid = get_current_activity_id ( 'lottery' );
        } else {
            get_current_activity_id ( 'lottery', $actid );
        }
        $where ['actid'] = $actid;

        // 表名
        $tblname = 'lottery_share';
        $name = '分享';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $field = 'a.id,a.memberid,a.date,a.num,b.nickname,b.username,b.mobile,b.headimgurl';
        $rs = M ( $tblname . ' as a ' )->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->order ( 'id desc' )->field ( $field )->page ( $p, $row );
        $list = $rs->select ();

        $this->assign ( "contentlist", $list );
        $count = $rs->join ( '__MEMBER__ as b on a.memberid=b.id' )->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        $this->assign ( "searchtype", $searchtype );
        $this->assign ( "status", $status );

        // 当前表名
        $control = 'prizetypelist';
        $action = 'Share';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        $actlist = get_cache_list ( 'lottery_activity', '', 'id,title' );
        $this->assign ( "actlist", $actlist );
        $this->assign ( 'actid', $actid );

        $this->assign ( "title", '分享列表' );
        $this->display ();
    }

}