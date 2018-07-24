<?php
namespace Common\Controller;

use Common\Lib\ExceptionHandler;
use Think\Controller;

class IController extends BaseController {

    public $member_id;

    /**
     * 初始化的时候，重新定义Api模块的错误处理函数
     * IController constructor.
     */
    public function __construct()
    {
        $this->member_id = IV('token','require|token');
        if(!$this->member_id)
        {
            IE('Token 错误');
        }
    }

    // 校验 token
    public function checkToken($token){

        $co=array(
            'token'=>$token,
        );
        $it = M('user_token')->field('user_id')->where($co)->find();
        if($it){
            return $it['user_id'];
        }else{
            IE(ERROR_USER_UNVALID_TOKEN);
        }
    }
}