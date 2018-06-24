<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2016/12/26
 * Time: 21:26
 */

namespace Admin\Controller;


class PrizeController extends BaseController
{
    /**
     * 转盘奖品列表
     */
    public function turntable($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1){
        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['name'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['content'] = array (
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
            case '3' :
                if (is_number ( $keyword )) {
                    $where ['pid'] = $keyword;
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


        // 表名
        $tblname = 'content_turntable';
        $name = '大转盘奖品';
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
        $control = 'Prize';
        $action = 'turntable';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '大转盘奖品列表' );
        $this->display ();
    }


    /**
     * 添加
     */
    public function addturntable() {
        $this->editturntable ();
    }
    /**
     * 添加修改课程
     *
     * @param number $id
     */
    public function editturntable($id = 0) {
        $tblname = 'content_turntable';
        $name = '大转盘奖品';
        $tplname = 'editturntable';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['title'] )) {
                $this->error ( '对不起，' . $name . '标题不能为空！' );
            }


            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();

                $this->success ( '恭喜，' . $name . '修改成功！',U('Prize/turntable') );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();
                M ( $tblname )->where ( array (
                    'id' => $id
                ) )->setField ( 'sort', $id );

                $this->success ( '恭喜，' . $name . '添加成功！',U('Prize/turntable')  );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
                // 修改的时候取父ID参数

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }

            $control = 'Prize';
            $action = 'turntable';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除课程
     *
     * @param number $id
     */
    public function deleteturntable($id = 0) {
        $tblname = 'content_turntable';
        $name = '大转盘奖品';
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



}