<?php
namespace Rest3\Model;

use Common\Lib\ArrayUtils;
use Common\Lib\LangUtils;

class FollowModel extends IModel{

    public function getStarCount($user_id){

        $co=array(
            'follower_user_id'=>$user_id,
            
        );
        return $this->where($co)->count();
    }

    public function getFollowerCount($user_id){

        $co=array(
            'star_user_id'=>$user_id,
        );
        return $this->where($co)->count();
    }

    public function hasFollow($follower_user_id,$star_user_id){

        $co=array(
            'follower_user_id'=>$follower_user_id,
            'star_user_id'=>$star_user_id,
        );

        if($this->where($co)->count()==0){
            return false;
        }else{
            return true;
        }
    }
}