<?php

require_once("TaskUtil.php");
// PatchByCode类提供方法将PatchCode以及data（如有的话）通过补充任务接口提交给服务器。
// 该代码仅用作演示接口调用，所有数据全部是虚拟数据，调用者根据自己情况来设置。
class PatchByCode{

    /**
     * Code : 2000
     * Describe : 手机短信验证码提交平台，请验证
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2000($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2000 ,
            "data" => "260483"
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2001
     * Describe : 请重发手机短信验证码
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2001($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2001
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2002
     * Describe : 图片验证码已提交，请验证
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2002($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2002 ,
            "data" => "k6ns"
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2003
     * Describe : 请重发图片验证码
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2003($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2003
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2004
     * Describe : 用户已扫描二维码并确认，请登陆
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2004($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2004
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2005
     * Describe : 账号，密码已提交，请登陆
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2005($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2005,
            "data"=>array(
                "account"=>"13987896567",
                "password"=>"986342"
            )
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2006
     * Describe : 身份证号码和姓名已提交，请验证
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2006($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2006,
            "data"=>array(
                "idCardNo"=>"312301198305060832",
                "realName"=>"王五"
            )
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2007
     * Describe : 请重发二维码图片
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2007($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2007
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2010
     * Describe : 人行登录信息已提交，请验证
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2010($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2010,
            "data"=>array(
                "account"=>"xdfddf23",
                "password"=>"827364kd",
                "smsCode"=>"25963"
            )
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2011
     * Describe : 独立密码已提交，请验证
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2011($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2011,
            "data"=> "433347"
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }

    /**
     * Code : 2014
     * Describe : 账户名、密码和图片验证码已提交，请验证
     * Date: 2017年7月13日
     * @param string $taskNo 任务号
     */
    public function patchByCode2014($taskNo){

        //模拟数据
        $post = array(
            "patchCode" => 2014,
            "data"=>array(
                "account"=>"test@126.com",
                "password"=>"827364kd",
                "captcha"=>"2ddf"
            )
        );

        //补充任务
        return TaskUtil::patchTask($taskNo ,$post);

    }
}