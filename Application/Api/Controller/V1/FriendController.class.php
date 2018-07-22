<?php
namespace Rest3\Controller\V1;

use Think\Controller;
use Common\Controller\IController;
use Common\Lib\PasswordUtil;
use Common\Lib\VercodeUitls;
use Common\Lib\ArrayUtils;
use Common\Lib\DateUtils;
use Common\Lib\FormatUtils;
use Common\Lib\CDNUtils;

/**
 * User信息
 */
class FriendController extends IController{

  	
  	// 导入电话
    public function importPhoneBook(){

    	$user_id = IV('token','require|token_check');
    	$phone_no = IV('user_phone','require');
    	$phone_book =IV('phone_book','require|json_format');// {"name":"","phone_no":""}

    	foreach ($phone_book as $item) {
			
    		$name = $item['name'];
    	 	$phone_no = $item['phone_no'];
    	 	if($name&&$phone_no){
    	 		$dt=array(
    	 			'phone_no'=>$phone_no,
    	 			'phone_no_name'=>$name,
    	 			'create_time'=>DateUtils::Now(),
    	 			'user_id'=>$user_id
    	 		);
    	 		M('phone_book')->add($dt);
    	 	}
    	}

    	// 更新用户的号码
    	M('user')->save(array(
    						'user_id'=>$user_id,
    						'phone_no'=>$phone_no
    					)
    	);

    	$this->iSuccess();
    }


    public function getFriendListFromPhoneBook(){

    	$user_id = IV('token','require|token');

    	$co=array(
    		'user_id'=>$user_id
    	);
    	$friendList = M('phone_book')->where($co)->field('phone_no,phone_no_name')->select();

    	foreach ($friendList as &$friend) {

    		$c=array('phone_no'=>$friend['phone_no']);
    		$friend_user_id = M('user')->where($c)->getField('user_id');

    		$u= D('User')->getInfo($friend_user_id);
    		$u['is_my_star'] = D('Follow')->hasFollow($user_id,$friend_user_id);
    		$u['is_my_follower'] = D('Follow')->hasFollow($friend_user_id,$user_id);
    		$friend['user_info'] = $u;
    	}

    	$this->iSuccess($friendList,'friendList');
    }


    public function getFriendListFromFacebook(){

        $user_id = IV('token','require|token_check');
        $facebook_friends =IV('facebook_friends','require|json_format');// {"id":"","name":""}

        $friendCount= 0;
        foreach ($facebook_friends as &$friend) {
            
            $fb_open_id = $friend['id'];
            $friend_user_id = M('user_open_platform')
                                ->where(array('open_id'=>$fb_open_id))
                                ->getField('user_id');

            if(!$friend_user_id){
                continue;
            }

            $u= D('User')->getInfo($friend_user_id);
            if($u){
                $u['is_my_star'] = D('Follow')->hasFollow($user_id,$friend_user_id);
                $u['is_my_follower'] = D('Follow')->hasFollow($friend_user_id,$user_id);
                if($u['is_my_star']&&$u['is_my_follower']){
                    $friendCount++;
                }
                $friend['user_info'] = $u;
            }
        }

        $this->iSuccess(array(
            'facebook_friends'=>$facebook_friends,
            'facebook_friend_count'=>$friendCount
        ));
    }
}