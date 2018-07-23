<?php
namespace Common\Controller;

use Common\Lib\ExceptionHandler;
use Think\Controller;

class BaseController extends Controller {



    public function iSuccess($data,$data_key) {

        if($data_key){
            $data=array("$data_key"=>$data);
        }

        $ret=array(
            'data' => $data,
            'status' => 1
        );

        if(!$data){
            unset($ret['data']);
        }

        return $this->ajaxReturn($ret);
    }

    public function iFailed($fail_key) {

        $ret=array(
            'data' => $fail_key,
            'status' => 0
        );

        return $this->ajaxReturn($ret);
    }

}