<?php

require_once("TaskUtil.php");
require_once("Config.php");

try {
    // 账号，密码登录淘宝
    $post = array(
        "callbackUrl" => "", //根据实际情况设置,如果采用查询方式获取结果，可以设为“”
        "data" => array(
            "loginType" => 0,
            "account" => "淘宝账号", //TODO 改为实际值
            "password" => "登录密码" //TODO 改为实际值
        )
    );

    $result = TaskUtil::submitTask(Config::TYPE_TAOBAO, $post);

    print_r($result);

} catch (Exception $e) {
    return $e;
}
