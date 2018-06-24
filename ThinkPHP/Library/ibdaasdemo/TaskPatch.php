<?php

require_once("PatchByCode.php");

try {
    // 任务号，通过TaskSubmit返回值获取
    $taskNo = 'f470019e71264deeb7c119888caafa9c1528467334149';
	$PatchByCode = new PatchByCode();

    $result = $PatchByCode->patchByCode2000($taskNo);

    print_r($result);

} catch (Exception $e) {
    return $e;
}
