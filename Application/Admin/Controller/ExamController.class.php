<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2018/2/7
 * Time: 14:53
 */

namespace Admin\Controller;


class ExamController extends BaseController
{
    /**
     * 题目列表
     */
    public function tiku($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
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
        }



        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }


        // 表名
        $tblname = 'question';
        $name = '题库';
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
        $control = 'Exam';
        $action = 'tiku';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '题目列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addtiku() {
        $this->edittiku ();
    }
    /**
     * 添加修改题目
     *
     * @param number $id
     */
    public function edittiku($id = 0) {
        $tblname = 'question';
        $name = '题目';
        $tplname = 'edittiku';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['title'] )) {
                $this->error ( '对不起，' . $name . '标题不能为空！' );
            }
            if (count ( $data ['items'] )<=0) {
                $this->error ( '对不起，' . $name . '选项不能为空！' );
            }
            if (count ( $data ['answer'] )<=0) {
                $this->error ( '对不起，' . $name . '答案不能为空！' );
            }

            $data['itemtitle']=json_encode($data['itemtitle'],JSON_UNESCAPED_UNICODE);
            $data['items']=json_encode($data['items'],JSON_UNESCAPED_UNICODE);
            $data['answer']=json_encode($data['answer'],JSON_UNESCAPED_UNICODE);



            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();

                $this->success ( '恭喜，' . $name . '修改成功！',U('Exam/tiku') );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();
                M ( $tblname )->where ( array (
                    'id' => $id
                ) )->setField ( 'sort', $id );

                $this->success ( '恭喜，' . $name . '添加成功！',U('Exam/tiku')  );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );

                $itemtitle=json_decode($db['itemtitle'],true);
                $items=json_decode($db['items'],true);
                $answer=json_decode($db['answer'],true);
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }




            $this->assign('itemtitle',$itemtitle);
            $this->assign('items',$items);
            $this->assign('answer',$answer);

            $control = 'Exam';
            $action = 'tiku';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除题目
     *
     * @param number $id
     */
    public function deletetiku($id = 0) {
        $tblname = 'question';
        $name = '题目';
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

    public function importstudent(){


        $this->assign('title','导入考生');
        $this->display();
    }


    /**
     * 考生列表
     */
    public function student($searchtype = '', $keyword = '', $pid = '', $status = '', $p = 1) {
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
        }



        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }


        // 表名
        $tblname = 'student';
        $name = '考生';
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
        $control = 'Exam';
        $action = 'student';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '考生列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addstudent() {
        $this->editstudent ();
    }
    /**
     * 添加修改考生
     *
     * @param number $id
     */
    public function editstudent($id = 0) {
        $tblname = 'student';
        $name = '考生';
        $tplname = 'editstudent';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['username'] )) {
                $this->error ( '对不起，' . $name . '姓名不能为空！' );
            }



            if ($id) {
                if(!isN($data['userpwd'])){
                    $data['userpwd']=md5( $data['userpwd']);
                }else{
                    unset($data['userpwd']);
                }

                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();

                $this->success ( '恭喜，' . $name . '修改成功！',U('Exam/student') );
            } else {

                $find=M('student')->where(array('telephone'=>$data['telephone']))->find();
                if($find){
                    $this->error ( '对不起，联系电话已存在！' );
                }

                $find=M('student')->where(array('no'=>$data['no']))->find();
                if($find){
                    $this->error ( '对不起，学号已存在！' );
                }


                if(isN($data['userpwd'])){
                    $this->error ( '对不起，密码不能为空！' );
                }
                $data['userpwd']=md5( $data['userpwd']);
                $id = M ( $tblname )->data ( $data )->add ();
                M ( $tblname )->where ( array (
                    'id' => $id
                ) )->setField ( 'sort', $id );

                $this->success ( '恭喜，' . $name . '添加成功！',U('Exam/student')  );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );

                $itemtitle=json_decode($db['itemtitle'],true);
                $items=json_decode($db['items'],true);
                $answer=json_decode($db['answer'],true);
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }



            $control = 'Exam';
            $action = 'student';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除考生
     *
     * @param number $id
     */
    public function deletestudent($id = 0) {
        $tblname = 'student';
        $name = '考生';
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