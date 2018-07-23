<?php
namespace Api\Model;

/**
 * 用户操作
 */
use Common\Lib\ArrayUtils;
use Common\Lib\CDNUtils;
use Common\Model\IModel;

class MemberModel extends IModel {



        public function getInfo($uid,$fields){

            if(!$fields){
                $fields = $this->main_fields;
            }

            $info = M('User')->cache(3600*24)->field($fields)->find($uid);
            CDNUtils::ImageCDNObj($info,'image');
            return $info;
        }

        public function getList($uids,$fields){

            if(!$fields){
                $fields = $this->main_fields;
            }

            if(empty($uids)){
                return array();
            }

            $co=array(
                'user_id'=>array('in',$uids),
                'status'=>1
            );

            $list = M('User')->where($co)->field($fields)->select();
            CDNUtils::ImageCDNArray($list,'image');

            return $list;
        }

    /**
     * 获得用户名，通过电话号码和密码
     * @param $telephone
     * @param $userpwd
     * @return array|bool|false|mixed|\PDOStatement|string|\think\Model
     */
        public function getUserByUsernameAndPass($telephone,$userpwd){
            $co=array(
                'telephone'=>$telephone,
                'userpwd'=>md5($userpwd)
            );
            $result = M('member')->where($co)->field('token')->find();
            if($result){
                return $result;
            }else{
                return false;
            }
        }


    /**
     * 注册验证码校验
     * @param string $type
     * @param string $mobile
     * @param string $code
     * @return array
     */
    public function verifySms($type = '', $mobile = '', $code = '') {
        $tblname = 'sms';
        $where = array ();
        $where ['mobile'] = $mobile;
        $where ['type'] = $type;
        $where ['code'] = $code;
        $where ['status'] = 0;
        $time = NOW_TIME - 60 * 10;
        $where ['addtime'] = array (
            'gt',
            time_format ( $time )
        );
        $find = M ( $tblname )->where ( $where )->order ( 'id desc' )->find ();
        if ($find) {
            $data = array ();
            $data ['verifytime'] = time_format ();
            $data ['status'] = 1;
            M ( $tblname )->where ( $where )->data ( $data )->save ();
            return true;
        } else {
            return false;
        }
    }


    /**
     * 保存用户
     * @param mixed|string $data
     */
    public function save($data)
    {
        $id = $data['id'];
        if(!id){
            M('member')->add($data);
        }else{
            $co['id'] = $id;
            M('member')->where($co)->save($data);
        }
    }

    /**
     * 生成Guid主键
     * @return Boolean
     */
     public function genToken() {
        return str_replace('-','',substr(String::uuid(),1,-1));
    }

    /**
     * 获取单个用户
     * @param $filter 过滤条件
     * @return array 返回用户
     */
    public function getOneUser($filter)
    {
        if(!$filter)    return;
        return M('member')->where($filter)->find();
    }


    /**
     * 保存用户信息
     */
    public function saveMember()
    {
        $member_id = $data['member_id'];

        $co = array();
        if($member_id)
        {
            $co['id'] = $member_id;
            M('member')->where($co)->save($data);
        }
        else
        {
            M('member')->add($data);
        }
    }

}