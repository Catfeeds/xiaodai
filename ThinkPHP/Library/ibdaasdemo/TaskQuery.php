<?php

require_once("TaskUtil.php");

try {

    // 任务号，通过TaskSubmit返回值获取
    $taskNo = '7a33ee9e22294ea699776e3de0d2aa4f1528468996700';

    $result = TaskUtil::queryTask($taskNo);

    print_r($result);

} catch (Exception $e) {
    return $e;
}
