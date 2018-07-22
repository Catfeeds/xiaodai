<?php
namespace Rest3\Model;

use Common\Lib\ArrayUtils;
use Common\Lib\CDNUtils;
use Common\Lib\FloatUtils;

class WalletModel extends IModel{

        
    public function getDiamondTotal($user_id){

        $co=array(
            'user_id'=>$user_id
        );
        $it = $this->where($co)->find();

        return $it['diamond_total'];
    }

    public function getTotal($user_id){

        $co=array(
            'user_id'=>$user_id
        );
        return $this->where($co)->find();
    }


    public function enoughDiamond($user_id,$cost_diamond_tatal){

        $total = $this->getDiamondTotal($user_id);
        if(FloatUtils::gte($total,$cost_diamond_tatal)){
            return true;
        }else{
            IE(ERROR_LESS_DIAMOND);
            return false;
        }
    }

    public function enoughCoin($user_id,$cost){

        $total = $this->getTotal($user_id);
        if(FloatUtils::gte($total['coin_total'],$cost)){
            return true;
        }else{
            IE(ERROR_LESS_COIN);
            return false;
        }
    }

    public function init($user_id){

        $co=array(
            'user_id'=>$user_id
        );
        $t = M('wallet')->where($co)->find();

        if(!$t){

            $dt=array(
                'user_id'=>$user_id
            );
            M('wallet')->add($dt);
        }
    }

    public function incTotal($user_id,$add_coin_total,$field){

        $co=array(
            'user_id'=>$user_id
        );
        M('wallet')->where($co)->setInc($field,$add_coin_total);
    }


    public function decTotal($user_id,$add_coin_total,$field){

        $co=array(
            'user_id'=>$user_id
        );
        M('wallet')->where($co)->setDec($field,$add_coin_total);
    }
}