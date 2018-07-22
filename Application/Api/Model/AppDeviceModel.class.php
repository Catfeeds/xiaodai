<?php
namespace Rest3\Model;

use Think\Model;
use Common\Lib\DateUtils;

class AppDeviceModel extends Model{

	
    #保持device信息
    public function addOrUpdate($data){

        #判断是否存在
        $device_id=$data['device_id'];
        $co=array(
            'device_id'=>$device_id
        );
        $n= $this->where($co)->count();
        if($n){

           $data['update_time'] = DateUtils::Now();
           $this->where($co)->save($data);
        }else{
           $this->add($data);
        }
    }
}