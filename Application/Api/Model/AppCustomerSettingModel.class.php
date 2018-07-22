<?php
namespace Rest3\Model;

use Think\Model;
use Common\Lib\String;
use Common\Lib\Date;

/**
 * 目前存放在mysql里面,后面迁移到memcached里面去。
 */
class AppCustomerSettingModel extends Model{

	
    public function getSetting($customer_id){

        $setting_json = $this->where(array('customer_id'=>$customer_id))->cache(true)->getField('setting_json');

        if($setting_json){
            return json_decode($setting_json,true);
        }else{
            return array();
        }
    }

    public function updateAllSetting($customer_id,$setting_json){

        $data=array(
            'setting_json'=>$setting_json,
            'updated'=>Date::Now(),          #更新时间
            'customer_id'=>$customer_id
        );
        
        $b=$this->_is_set($customer_id);
        if(!$b){
            $this->add($data);
        }else{
            $n=$this->save($data);
        }
    }

    /** [_is_set description] */
    public function _is_set($customer_id){

        $co=array('customer_id'=>$customer_id);
        $n=$this->where($co)->count();
        if($n){
            return true;
        }else{
            return false;
        }
    }

}