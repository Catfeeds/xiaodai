<?php

namespace Common\Lib;

class RateUtils{
   
    // 钻石换成钱
    public function getMoneyFromDiamond($diamond_total){
        return bcmul($diamond_total,C('Rate_Diamond'),2);
    }

    // 钻石换成钱
    public function getDiamondFromCoin($coin_total){
        return bcmul($coin_total,C('Rate_Coin'),2);
    }

    // 钻石换成钱
    public function getDiamondFromMoney($total){
        return bcmul($total,1/C('Rate_Diamond'),2);
    }
}