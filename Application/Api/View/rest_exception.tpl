<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

#设置框架的状态码
send_http_status(200);

$arr=explode("#",$e['message']);

if(count($arr)!=2){
    $mess= L(ERROR_UNKONW);
    $arr=explode("#", $mess);
}

$data=array(
	'info'=>$arr[1],
	'status'=>intval($arr[0])
);
$js=json_encode($data);
echo $js;
?>