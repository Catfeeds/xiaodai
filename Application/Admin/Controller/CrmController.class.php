<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/5/18
 * Time: 13:20
 */

namespace Admin\Controller;


class CrmController extends BaseController
{

    /**
     * 员工列表
     */
    public function staff($searchtype = '', $keyword = '', $status = '', $p = 1,$departmentid='') {

        $where = array ();
        switch ($searchtype) {
            case '0' :
                if (! isN ( $keyword )) {
                    $where ['username'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
            case '1' :
                if (! isN ( $keyword )) {
                    $where ['name'] = array (
                        'like',
                        '%' . $keyword . '%'
                    );
                }
                break;
        }

        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        if($departmentid){
            $where['departmentid']=$departmentid;
        }

        // 表名
        $tblname = 'staff';
        $name = '员工';
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
        $this->assign ( "departmentid", $departmentid );

        // 当前表名
        $control = 'Crm';
        $action = 'staff';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        //所有员工
        $allstaff=M('staff')->where(array('status'=>1))->select();
        $this->assign('allstaff',$allstaff);

        //部门
        $department=M('department')->where(array('status'=>1))->select();
        $this->assign('department',$department);

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '员工列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addstaff() {
        $this->editstaff ();
    }
    /**
     * 添加修改员工
     *
     * @param number $id
     */
    public function editstaff($id = 0) {
        $tblname = 'staff';
        $name = '员工';
        $tplname = 'editstaff';
        if (IS_POST) {
            $data = $_POST;


            $data ['username'] = trim ( $data ['username'] );
            $data ['userpwd'] = trim ( $data ['userpwd'] );
            if (isN ( $data ['username'] )) {
                $this->error ( '对不起，' . $name . '用户名不能为空！' );
            }

            $data['departlimit']=json_encode($data['departlimit']);

            if ($id) {
                unset ( $data ['username'] );
                if (! isN ( $data ['userpwd'] )) {
                    $data ['userpwd'] = md5 ( $data ['userpwd'] );
                } else {
                    unset ( $data ['userpwd'] );
                }

                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                if (isN ( $data ['userpwd'] )) {
                    $this->error ( '对不起，' . $name . '密码不能为空！' );
                }

                $where = array ();
                $where ['username'] = $data ['username'];
                $find = M ( $tblname )->where ( $where )->find ();
                if ($find) {
                    $this->error ( '对不起，登录名【' . $data ['username'] . '】已存在！' );
                }
                $data ['userpwd'] = md5 ( $data ['userpwd'] );
                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );

                $limit=json_decode($db['departlimit'],true);
                $this->assign('limit',$limit);


                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
                // 修改的时候取父ID参数
                $this->assign ( "pid", $db ['pid'] );
            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }

            //部门列表
            $department=M('department')->where(array('status'=>1))->select();
            $this->assign('department',$department);

            $control = 'Crm';
            $action = 'staff';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }

    /**
     * 删除员工
     *
     * @param number $id
     */
    public function deletestaff($id = 0) {
        $tblname = 'staff';
        $name = '员工';
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
     *客户来源列表
     */
    public function source($searchtype = '', $keyword = '', $status = '', $p = 1) {

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
        }

        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        // 表名
        $tblname = 'member_source';
        $name = '客户来源';
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
        $control = 'Crm';
        $action = 'source';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '客户来源列表' );
        $this->display ();
    }
    /**
     * 添加
     */
    public function addsource() {
        $this->editsource ();
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function editsource($id = 0) {
        $tblname = 'member_source';
        $name = '客户来源';
        $tplname = 'editsource';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['name'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }


            $control = 'Crm';
            $action = 'source';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除
     *
     * @param number $id
     */
    public function deletesource($id = 0) {
        $tblname = 'member_source';
        $name = '客户来源';
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
     * 产品分类列表
     */
    public function producttype($searchtype = '', $keyword = '', $status = '', $p = 1) {

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
        }

        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        // 表名
        $tblname = 'member_producttype';
        $name = '产品类别';
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
        $control = 'Crm';
        $action = 'producttype';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '产品类别列表' );
        $this->display ();
    }
    /**
     * 添加
     */
    public function addproducttype() {
        $this->editproducttype ();
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function editproducttype($id = 0) {
        $tblname = 'member_producttype';
        $name = '产品类别';
        $tplname = 'editproducttype';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['name'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }


            $control = 'Crm';
            $action = 'producttype';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除
     *
     * @param number $id
     */
    public function deleteproducttype($id = 0) {
        $tblname = 'member_producttype';
        $name = '产品类别';
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
     * 客户分级列表
     */
    public function level($searchtype = '', $keyword = '', $status = '', $p = 1) {

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
        }

        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        // 表名
        $tblname = 'member_level';
        $name = '客户分级';
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
        $control = 'Crm';
        $action = 'level';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '客户分级列表' );
        $this->display ();
    }
    /**
     * 添加
     */
    public function addlevel() {
        $this->editlevel ();
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function editlevel($id = 0) {
        $tblname = 'member_level';
        $name = '客户分级';
        $tplname = 'editlevel';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['name'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }


            $control = 'Crm';
            $action = 'level';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除
     *
     * @param number $id
     */
    public function deletelevel($id = 0) {
        $tblname = 'member_level';
        $name = '客户分级';
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
     * 客户状态列表
     */
    public function memberstatus($searchtype = '', $keyword = '', $status = '', $p = 1) {

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
        }

        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        // 表名
        $tblname = 'member_status';
        $name = '客户状态';
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
        $control = 'Crm';
        $action = 'memberstatus';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '客户状态列表' );
        $this->display ();
    }
    /**
     * 添加
     */
    public function addmemberstatus() {
        $this->editmemberstatus ();
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function editmemberstatus($id = 0) {
        $tblname = 'member_status';
        $name = '客户状态';
        $tplname = 'editmemberstatus';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['name'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }

            //若设置为成交状态，则先将其他的成交状态设置为否
            if($data['complate']==1){
                M ( $tblname )->setField ( 'complate', 0 );
            }


            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );

            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }


            $control = 'Crm';
            $action = 'memberstatus';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除
     *
     * @param number $id
     */
    public function deletememberstatus($id = 0) {
        $tblname = 'member_status';
        $name = '客户状态';
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
     * 客户列表
     */
    public function member($keyword = '', $pid = '',$sex='', $status = '',$applystatus='', $p = 1,$staffid='',$sourceid="",$levelid="",$producttypeid="",$memberstatusid="") {

        $where = array ();
        $map=array();

        //员工
        $stafflist=M('staff')->where(array('status'=>1))->select();
        $this->assign('stafflist',$stafflist);

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

        if (! isN ( $keyword )) {
            $map ['company'] = array (
                'like',
                '%' . $keyword . '%'
            );
            $map ['userreal'] = array (
                'like',
                '%' . $keyword . '%'
            );
            $map ['address'] = array (
                'like',
                '%' . $keyword . '%'
            );
            $map ['telephone'] = array (
                'like',
                '%' . $keyword . '%'
            );
            $map ['targetamount'] = array (
                'like',
                '%' . $keyword . '%'
            );

            $map['_logic']='or';
            $where['_complex']=$map;
        }

        if($sex>0){
            $where['sex']=$sex;
        }
        $this->assign('sex',$sex);



        if (is_numeric ( $pid )) {
            $where ['pid'] = $pid;
        }
        if (is_numeric ( $status )) {
            $where ['status'] = $status;
        }

        if(is_numeric ( $applystatus )){
            $where ['applystatus'] = $applystatus;
        }

        if($staffid>0){
            $where ['staffid'] = $staffid;
        }

        if($sourceid>0){
            $where ['source'] = $sourceid;
        }
        if($producttypeid>0){
            $where ['producttype'] = $producttypeid;
        }
        if($levelid>0){
            $where ['level'] = $levelid;
        }
        if($memberstatusid>0){
            $where ['memberstatus'] = $memberstatusid;
        }

        //if($searchtype!=1){
            $where['telephone']=array('neq','');
        //}


        // 表名
        $tblname = 'member';
        $name = '客户';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
        $list = $rs->select ();

        foreach($list as $key=>$val){
            $record=M('content_signup_record')->where(array('memberid'=>$val['id']))->select();
            $times=count($record);
            $list[$key]['signuptimes']=$times;
        }

        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        //$this->assign ( "searchtype", $searchtype );
        $this->assign ( "status", $status );
        $this->assign ( "applystatus", $applystatus );
        $this->assign ( "staffid", $staffid );

        $this->assign ( "sourceid", $sourceid );
        $this->assign ( "producttypeid", $producttypeid );
        $this->assign ( "levelid", $levelid );
        $this->assign ( "memberstatusid", $memberstatusid );

        // 已有分类列表
        $list = get_cache_list ( 'category_member' );
        $list = list_to_tree ( $list );
        $this->assign ( "list", $list );

        // 当前表名
        $control = 'Crm';
        $action = 'member';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "pid", $pid );
        $this->assign ( "staffid", $staffid );
		
		$complate=getcomplatestatus();
        $this->assign('complate',$complate);


        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign('applystatuslist',array(0=>'未申请',1=>'已申请'));

        $this->assign ( "title", '客户列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addmember() {
        $this->editmember ();
    }
    /**
     * 添加修改客户
     *
     * @param number $id
     */
    public function editmember($id = 0) {
        $tblname = 'member';
        $name = '客户';
        $tplname = 'editmember';
        if (IS_POST) {
            $data = $_POST;

            if(isN($data['telephone'])){
                $this->error ( '对不起，请输入联系电话！' );
            }

            $newcol=$data['colname'];
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


            $data ['username'] = trim ( $data ['username'] );

            $extentinfo=array();
            foreach($data['extendname'] as $kn=>$vn){
                $extentinfo[$kn]['name']=$vn;
                $extentinfo[$kn]['content']=$data['extendcontent'][$kn];
            }

            $data['extendinfo']=json_encode($extentinfo);




            if ($id) {
                unset ( $data ['username'] );
                
                $find=M ( $tblname )->where ( array (
                    'id' => $id
                ) )->getField("complatetime");
                if(!$find){
                    $complate=getcomplatestatus();
                    if($data['memberstatus']==$complate){
                        $data['complatetime']=date("Y-m-d H:i:s");
                    }
                }
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
				
				//dump(M ( $tblname )->getLastSql());die;
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $complate=getcomplatestatus();
                if($data['memberstatus']==$complate){
                    $data['complatetime']=date("Y-m-d H:i:s");
                }

                $where = array ();
                $where ['username'] = $data ['username'];
                $find = M ( $tblname )->where ( $where )->find ();
                if ($find) {
                    $this->error ( '对不起，用户名【' . $data ['username'] . '】已存在！' );
                }
                $data ['userpwd'] = md5pwd ( $data ['userpwd'] );
                $id = M ( $tblname )->data ( $data )->add ();
                M ( $tblname )->where ( array (
                    'id' => $id
                ) )->setField ( 'sort', $id );
                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {


                $where = array ();
                // $where['fpath']=array('like','%,'.$id.',%');
                $where ['fid'] = $id;
//				$field = 'id,fid,invitecode,nickname,username';
                $nodelist = M ( $tblname )->where ( $where )->order ( 'id desc' )->limit(100)->select ();

                foreach ( $nodelist as $k => $v ) {
                    $nodelist [$k] ['name'] = $v ['username'];

                    $isp=M('member')->where(array('fid'=>$v['id']))->count();
                    $nodelist [$k] ['isParent'] = $isp>0 ? true : false;
                }

                $this->assign ( 'nodelist', $nodelist );
   //跟进记录
                $followlist=M('staff_follow')->where(array('memberid'=>$id))->order('addtime desc')->select();
                $this->assign('followlist',$followlist);

                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
                // 修改的时候取父ID参数
                $this->assign ( "pid", $db ['pid'] );
            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }

            ////房源字典列表
            //$list=M('house_dic')->where(array('pid'=>0,'status'=>1))->select();
            //foreach($list as $key =>$val){
            //    $down=M('house_dic')->where(array('pid'=>$val['id'],'status'=>1))->select();
            //    $list[$key]['down']=$down;
            //}
            //$this->assign('list',$list);

            //客户分级
            $memberlevel=M('member_level')->where(array('status'=>1))->select();
            $this->assign('memberlevel',$memberlevel);
            //客户来源
            $membersource=M('member_source')->where(array('status'=>1))->select();
            $this->assign('membersource',$membersource);
            //客户分类
            $memberstatus=M('member_status')->where(array('status'=>1))->select();
            $this->assign('memberstatus',$memberstatus);

            //产品类别
            $producttype=M('member_producttype')->where(array('status'=>1))->select();
            $this->assign('producttype',$producttype);


            $control = 'Crm';
            $action = 'member';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }

    /**
     * 删除客户
     *
     * @param number $id
     */
    public function deleteMember($id = 0) {
        $tblname = 'member';
        $name = '客户';
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
	
	
	
    public function stafffollow($timefrom="",$timeto="",$staffid="",$p=""){
        $where = array ();

        if(!isN($timefrom)){
            $where['sf.addtime']=array('gt',$timefrom);
        }
        if(!isN($timeto)){
            $timeto = get_date_add ( $timeto, 1 );
            $where['sf.addtime']=array('lt',$timeto);
        }

        if(!isN($timefrom) && !isN($timeto)){
            $where['sf.addtime']=array('between',array($timefrom,$timeto));
        }

        if($staffid>0){
            $where ['sf.staffid'] = $staffid;
        }



        // 表名
        $tblname = 'staff_follow as sf';
        $name = '客户';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $field="sf.*,mm.userreal,mm.address as maddress,mm.telephone as mtelephone,mm.company as musername";
        $rs = M ( $tblname )
            ->join('my_member as mm on mm.id=sf.memberid')
            ->field($field)
            ->where ( $where )->order ( 'sf.addtime desc' )->page ( $p, $row );
        $list = $rs->select ();



        $this->assign ( "contentlist", $list );
        $count =M ( $tblname )
            ->join('my_member as mm on mm.id=sf.memberid')
            ->join('my_staff as s on s.id=sf.staffid')
            ->field($field)
            ->where ( $where )->order ( 'sf.addtime desc' )->count ();

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        // 当前表名
        $control = 'Crm';
        $action = 'stafffollow';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        $this->assign ( "staffid", $staffid );
		
		
		//
		$stafflist=M('staff')->select();
		$this->assign('stafflist',$stafflist);

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );



        $this->assign ( "title", '客户跟进记录' );
        $this->display ();
    }

    public function editstafffollow($id=""){
        $tblname = 'staff_follow';
        $name = '客户跟进详细';
        $tplname = 'editstafffollow';

            // 修改
        $db = M($tblname)->find($id);
        $this->assign('db', $db);

        $this->assign('title', '查看' . $name . '详细');
        $member=M('member')->where(array('id'=>$db['memberid']))->find();
        $staff=M('staff')->where(array('id'=>$db['staffid']))->find();
        $this->assign('member',$member);
        $this->assign('staff',$staff);
        $control = 'Crm';
        $action = 'stafffollow';
        $this->assign("control", $control);
        $this->assign("action", $action);

        $this->assign("name", $name);
        $this->display($tplname);

    }

    public function deletestafffollow($id = 0) {
        $tblname = 'staff_follow';
        $name = '客户跟进记录';
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

	
	
    public function customdic(){
        $where = array ();

        $where['pid']=0;

        // 表名
        $tblname = 'member_dic';
        $name = '客户字典';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' );
        $list = $rs->select ();


        $this->assign ( "contentlist", $list );


        // 当前表名
        $control = 'Crm';
        $action = 'customdic';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '客户字典' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addcustomdic() {
        $this->editcustomdic ();
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function editcustomdic($id = 0) {
        $tblname = 'member_dic';
        $name = '客户字典';
        $tplname = 'editcustomdic';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['name'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
                $this->assign('pid',$db['pid']);
            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }

            //父级
            $list=M('member_dic')->where(array('pid'=>0))->select();
            $this->assign('list',$list);
            $control = 'Crm';
            $action = 'customdic';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除
     *
     * @param number $id
     */
    public function deletecustomdic($id = 0) {
        $tblname = 'member_dic';
        $name = '客户字典';
        $find = M ( $tblname )->find ( $id );
        if ($find) {
            M ( $tblname )->where ( array (
                'pid' => $id
            ) )->delete ();
            M ( $tblname )->where ( array (
                'id' => $id
            ) )->delete ();
            $this->success ( '恭喜，' . $name . '已删除！' );
        } else {
            $this->error ( '对不起，' . $name . '不存在！' );
        }
    }



    public function applymember($memberid=''){

        $member=M('member')->where(array('id'=>$memberid))->find();
        $this->assign('member',$member);

        $record=M('member_apply')->where(array('memberid'=>$memberid,'status'=>0))->find();
		
		

        $staff=M('staff')->where(array('id'=>$record['staffid']))->find();

        $this->assign('staff',$staff);
        $this->assign('memberid',$memberid);

        $this->display();
    }


   
    /**
     * 导入会员
     */
    public function import(){
        if(IS_POST){
            $return=array();
            $fname=$_FILES['file']['tmp_name'];
            $handle = fopen($fname, 'r');
            if(!$handle){
                $this->error('没有要导入的文件');
            }
            $row=1;
            $newrow=0;
            $result=array();
            while($data=fgetcsv($handle,null,",")){
//                dump($data);
                $num=count($data);
                $row ++;
                for ($c=0;$c<$num;$c++){
                    $data[$c] = trim(iconv('gb2312', 'utf-8',$data[$c]));
                }
                $result[$newrow]=$data;
                $newrow++;
            }
           unset($result[0]);
            if(count($result)<=0){
                $this->error('导入的文件中没有数据');
            }


            $savedata=array();
            $unsavedata=array();

            for($i=1;$i<=count($result);$i++){

                if(!$result[$i][1] || !$result[$i][8]){
                    $unsavedata[$i]=$result[$i];
                    $unsavedata[$i][3]='手机号或姓名未填写';
                    continue;
                }

                if(!is_mobile($result[$i][8])){
                    $unsavedata[$i]=$result[$i];
                    $unsavedata[$i][3]='手机号格式错误';
                }

                $where=array();
                $where['telephone']=$result[$i][8];

                $exist=M('member')->where($where)->find();

                if($exist){
                    $unsavedata[$i]=$result[$i];
                    $unsavedata[$i][3]='已经存在的手机号';
                    continue;
                }

                $savedata[$i]['userreal']=$result[$i][1];
                $savedata[$i]['company']=$result[$i][2];
                $savedata[$i]['sex']=$result[$i][3];
                $savedata[$i]['age']=$result[$i][4];
                $savedata[$i]['education']=$result[$i][5];
                $savedata[$i]['career']=$result[$i][6];
                $savedata[$i]['income']=$result[$i][7];
                $savedata[$i]['telephone']=$result[$i][8];
                $savedata[$i]['fid']=$result[$i][9];
                if($result[$i][3]){
                    $sortpath=M('member')->where(array('id'=>$result[$i][3]))->getField('sortpath');
                }else{
                    $sortpath='';
                }

                $savedata[$i]['sortpath']=$sortpath;//先记录父级sortpath，然后再插入数据库之后保存完成sortpath
                $savedata[$i]['pid']=$result[$i][10];

            }

            foreach($savedata as $key=>$val){
                $val['addtime']=date('Y-m-d H:i:s');
                $savesortpath=$val['sortpath'];
                unset($val['sortpath']);
                $save=M('member')->data($val)->add();
                if($save===false){

                    $unsavedata[$key]=$savedata[$key];
                    $unsavedata[$i][3]='数据库保存失败，请重试';
                    unset($savedata[$key]);
                }
                M('member')->where(array('id'=>$save))->setField('sortpath',$savesortpath.','.$save);
            }

            $return['save']=count($savedata);
            $return['unsave']=$unsavedata;
            $return['allnum']=count($result);
            $this->success($return);

            exit();
        }
        $this->assign('title','导入会员');
        $this->display();

    }




    public function department(){
        $where = array ();
        // 表名
        $tblname = 'department';
        $name = '部门';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' );
        $list = $rs->select ();
        $this->assign ( "contentlist", $list );
        // 当前表名
        $control = 'Crm';
        $action = 'department';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '部门' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function adddepartment() {
        $this->editdepartment();
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function editdepartment($id = 0) {
        $tblname = 'department';
        $name = '部门';
        $tplname = 'editdepartment';
        if (IS_POST) {
            $data = $_POST;

            if (isN ( $data ['name'] )) {
                $this->error ( '对不起，' . $name . '名称不能为空！' );
            }

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！' );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();

                $this->success ( '恭喜，' . $name . '添加成功！' );
            }
            exit ();
        } else {
            if ($id) {
                // 修改
                $db = M ( $tblname )->find ( $id );
                $this->assign ( 'db', $db );
                $this->assign ( 'title', '修改' . $name . '【' . $id . '】' );
                $this->assign('pid',$db['pid']);
            } else {
                // 添加
                $this->assign ( 'title', '添加' . $name );
            }


            $control = 'Crm';
            $action = 'department';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }
    /**
     * 删除
     *
     * @param number $id
     */
    public function deletedepartment($id = 0) {
        $tblname = 'department';
        $name = '部门';
        $find = M ( $tblname )->find ( $id );
        if ($find) {
            M ( $tblname )->where ( array (
                'pid' => $id
            ) )->delete ();
            M ( $tblname )->where ( array (
                'id' => $id
            ) )->delete ();
            $this->success ( '恭喜，' . $name . '已删除！' );
        } else {
            $this->error ( '对不起，' . $name . '不存在！' );
        }
    }


    /**
     * crm销售统计
     *
     * @param number $id
     */
    public function saletongji(){

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
        $this->assign('title','CRM销售统计');
        $this->display();
    }

    public function showrankdetail($type="",$masterid="",$p="",$keyword=""){
        $staff=M('staff')->where(array('id'=>$masterid))->find();
        $this->assign('staff',$staff);
        // 分页
        $row = 5;
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

                $rs = M ( 'member' )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
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

                $rs = M ( 'contract' )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
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

                $rs = M ( 'item' )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
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
                    ->where ( $where )->field($field)->order ( 'id desc' )->page ( $p, $row );
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



        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }
        $this->assign ( "keyword", $keyword );
        $this->assign ( "type", $type );
        $this->assign ( "masterid", $masterid );

        $this->display ();
    }


    public function itemtongji($keyword = '', $p = 1,$staffid=''){
        $where = array ();
        $map=array();

        //员工
        $stafflist=M('staff')->where(array('status'=>1))->select();
        $this->assign('stafflist',$stafflist);



        if (! isN ( $keyword )) {
            $map ['name'] = array (
                'like',
                '%' . $keyword . '%'
            );
            $map ['mastername'] = array (
                'like',
                '%' . $keyword . '%'
            );

            $map['_logic']='or';
            $where['_complex']=$map;
        }



        if($staffid>0){
            $where ['masterid'] = $staffid;
        }

        // 表名
        $tblname = 'item';
        $name = '项目';
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
        $this->assign ( "staffid", $staffid );

        // 当前表名
        $control = 'Crm';
        $action = 'itemtongji';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "staffid", $staffid );


        $this->assign ( "title", '项目列表' );
        $this->display();
    }


    public function hetongtongji($keyword = '', $p = 1,$staffid=''){
        $where = array ();
        $map=array();

        //员工
        $stafflist=M('staff')->where(array('status'=>1))->select();
        $this->assign('stafflist',$stafflist);

        if (! isN ( $keyword )) {
            $map['name']=array('like','%'.$keyword.'%');
            $map['mastername']=array('like','%'.$keyword.'%');
            $map['no']=array('like','%'.$keyword.'%');
            $map['_logic']='or';
            $where['_complex']=$map;
        }

        if($staffid>0){
            $where ['masterid'] = $staffid;
        }

        // 表名
        $tblname = 'contract';
        $name = '合同';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' )->page ( $p, $row );
        $list = $rs->select ();

        foreach($list as $k=>$v){
            $now=date('Y-m-d H:i:s');
            $left=diffBetweenTwoDaysreal($now,$v['end']);
            $left=intval($left);
            if($left<=0){
                $list[$k]['left']=0;
                $list[$k]['color']='red';
            }else{
                $list[$k]['left']=$left;
                if($left<15){
                    $color="orangered";
                }else{
                    $color="green";
                }
                $list[$k]['color']=$color;
            }
        }
        $this->assign ( "contentlist", $list );
        $count = $rs->where ( $where )->count ();


        $all=M('contract')->where($where)->field('sum(amount) as amount,sum(getamount) as getamount')->select();
        $allamount=$all[0]['amount'];
        $getallamount=$all[0]['getamount'];

        $this->assign('allamount',$allamount);
        $this->assign('getallamount',$getallamount);

        if ($count > $row) {
            $page = new \Think\Page ( $count, $row );
            $page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
            $this->assign ( 'page', $page->show () );
        }

        $this->assign ( "keyword", $keyword );
        $this->assign ( "staffid", $staffid );

        // 当前表名
        $control = 'Crm';
        $action = 'hetongtongji';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        $this->assign ( "staffid", $staffid );


        $contract_timeout=C('config.CONTRACT_TIMEOUT');
        $this->assign('contract_timeout',$contract_timeout);

        $this->assign ( "title", '合同列表' );
        $this->display();
    }



}