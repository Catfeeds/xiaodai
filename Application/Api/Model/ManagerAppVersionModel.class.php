<?php
namespace Rest3\Model;

use Think\Model;

class ManagerAppVersionModel extends Model{

	
    #获取最新的app版本
    public function getCheckedVersion($app_type,$app_bundle_id){

        $co=array(
            'checked'=>true,
            'type'=>$app_type,
            #'bundle_id'=>$app_bundle_id
        );
        $fields='version,
                info,
                created,
                force_update,
                app_link';

        $it= $this->where($co)->field($fields)->order('version desc')->find();
        return $it;
    }

}