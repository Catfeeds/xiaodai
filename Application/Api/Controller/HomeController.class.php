<?php
namespace Api\Controller;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\DateUtils;

class LogController extends IController{


    public function home(){

        $logtypename = I('logtypename');
        $logcontent = I('logcontent');
        $logtime = I('logtime');

        $dt=array(
            'logtypename'=>$logtypename,
            'logcontent'=>$logcontent,
            'logtime'=>$logtime,
            'create_time'=>DateUtils::Now()
        );
        M('log')->add($dt);

        $this->iSuccess();
    }

}