<?php
/**
 * Created by PhpStorm.
 * User: YJ
 * Date: 2017/12/26
 * Time: 13:29
 */

namespace Admin\Controller;


class HouseController extends BaseController
{

    public function index(){

    }

    /**
     * 房源列表
     */
    public function house($status="", $p = 1) {

        $param = $_GET;
        unset ( $param [C('DOMAIN_PARAM')] );
        $param = array_change_key_case ( $param, CASE_LOWER );
        unset($param['p']);
        unset($param['status']);

        $pacount=0;
        if(count($param)>0){
            $pacount=1;
        }

        $where = array ();
        foreach($param as $k=>$v){
            if($k=='title'){
                $where[$k]=array('like','%'.$v.'%');
                $this->assign('keyword',$v);
            }else{
                $where[$k]=$v;
                $this->assign('c_'.$k.'',$v);
            }
        }
        if(is_numeric($status)){
            $where['status']=$status;
            $pacount=1;
        }
        $this->assign('pacount',$pacount);

        // 表名
        $tblname = 'house';
        $name = '房源';
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

        $this->assign ( "status", $status );

        //房源字典列表
        $list=M('house_dic')->where(array('pid'=>0,'status'=>1))->select();
        foreach($list as $key =>$val){
            $down=M('house_dic')->where(array('pid'=>$val['id'],'status'=>1))->select();
            $list[$key]['down']=$down;
        }
        $this->assign('list',$list);
//        dump($list);die;

        // 当前表名
        $control = 'House';
        $action = 'house';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );
        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '房源列表' );
        $this->display ();
    }

    /**
     * 添加
     */
    public function addhouse() {
        $this->edithouse ();
    }

    /**
     * 添加修改房源
     *
     * @param number $id
     */
    public function edithouse($id = 0) {
        $tblname = 'house';
        $name = '房源';
        $tplname = 'edithouse';
        if (IS_POST) {
            $data = $_POST;

            $newcol=$data['colname'];
            $sql="desc my_house";
            $oldcollist=M()->query($sql);
            $oldcol=array();
            foreach($oldcollist as $kn=>$vn){
                $oldcol[$kn]=$vn['field'];
            }


            foreach($newcol as $k=>$v){
                if(!in_array($v,$oldcol)){
                    //添加字段
                    $sql = "ALTER TABLE ".C('DB_PREFIX')."house ADD ".$v." VARCHAR(255);";
                    M() -> execute($sql);
                }

            }




            $data ['images'] = json_encode ( $data ['images'] );

            if ($id) {
                $db = M ( $tblname )->where ( array (
                    'id' => $id
                ) )->data ( $data )->save ();
                $this->success ( '恭喜，' . $name . '修改成功！',U('House/house') );
            } else {

                $id = M ( $tblname )->data ( $data )->add ();
                $this->success ( '恭喜，' . $name . '添加成功！',U('House/house')  );
            }
            exit ();
        } else {
            if ($id) {
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

            //房源字典列表
            $list=M('house_dic')->where(array('pid'=>0,'status'=>1))->select();
            foreach($list as $key =>$val){
                $down=M('house_dic')->where(array('pid'=>$val['id'],'status'=>1))->select();
                $list[$key]['down']=$down;
            }
            $this->assign('list',$list);


            $control = 'House';
            $action = 'house';
            $this->assign ( "control", $control );
            $this->assign ( "action", $action );

            // 状态标识
            $this->assign ( "statuslist", C ( 'STATUS' ) );
            $this->assign ( "name", $name );
            $this->display ( $tplname );
        }
    }

    /**
     * 删除房源
     *
     * @param number $id
     */
    public function deletehouse($id = 0) {
        $tblname = 'house';
        $name = '房源';
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
     * 数据字典
     *
     * @param number $id
     */
    public function housedic(){
        $where = array ();

        $where['pid']=0;

        // 表名
        $tblname = 'house_dic';
        $name = '房源字典';
        // 分页
        $row = C ( 'VAR_PAGESIZE' );
        $rs = M ( $tblname )->where ( $where )->order ( 'id desc' );
        $list = $rs->select ();


        $this->assign ( "contentlist", $list );


        // 当前表名
        $control = 'House';
        $action = 'housedic';
        $this->assign ( "control", $control );
        $this->assign ( "action", $action );
        $this->assign ( "tblname", $tblname );
        $this->assign ( "name", $name );

        // 状态标识
        $this->assign ( "statuslist", C ( 'STATUS' ) );

        $this->assign ( "title", '房源字典' );
        $this->display ();
    }
    /**
     * 添加
     */
    public function addhousedic($pid="") {
        $pid=$pid?$pid:0;

        $this->edithousedic ($id=0,$pid);
    }
    /**
     * 添加修改
     *
     * @param number $id
     */
    public function edithousedic($id = 0,$pid="") {
        $tblname = 'house_dic';
        $name = '房源字典';
        $tplname = 'edithousedic';
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
                $this->assign('pid',$pid);
            }

            //父级
            $list=M('house_dic')->where(array('pid'=>0))->select();
            $this->assign('list',$list);
            $control = 'House';
            $action = 'housedic';
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
    public function deletehousedic($id = 0) {
        $tblname = 'house_dic';
        $name = '房源字典';
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


    public function import(){
        $this->assign('title','导入房源');
        $this->display();
    }



}