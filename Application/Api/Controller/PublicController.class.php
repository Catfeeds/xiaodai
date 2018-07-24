<?php
namespace Api\Controller;

/**
 * 用户相关接口
 */
use Common\Controller\BaseController;
use Common\Controller\IController;
use Common\Lib\DateUtils;
use Think\Controller;


/**
 * User登录
 */
class PublicController extends BaseController {

    /**
     *用户登录接口
     */
    public function login()
    {
        $telephone = IV('telephone','require');
        $password=IV('password','require');

        $user = D('Member')->getUserByUsernameAndPass($telephone,$password);
        if(!$user) {
            IE("手机号或密码错误",'');
        }
        $this->iSuccess($user,'member');
    }

}