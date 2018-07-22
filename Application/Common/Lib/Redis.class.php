<?php

namespace Common\Lib;

class Redis{

    protected $redis;
    
    public function set($key,$value,$expireTime=null){

        $value=serialize($value);
        $redis->set($key,$value);
        if($expireTime){
            $redis->expire($key,$expireTime);
        }
    }

    public function get($key){

        $v = $redis->get($key);
        if(!$v){
            return null;
        }else{
            return unserialize($v);
        }
    }

    public function ttl($key){
        return $redis->ttl($key);
    }

    public function __construct() {
        $this->redis = new \Redis();
        $this->redis->connect(C(REDIS_DB_HOST),C(REDIS_DB_PORT));
    }
}