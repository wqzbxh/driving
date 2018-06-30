<?php 
namespace app\admin\model;
use think\Model;

/**
 * 用户模型
 * @author zhuyong
 * @date 2017-08-31 14:11
 * @version 1.0
 */

class Users extends Model
{
    // 表名	
    private $table_name = 'Users'; 
    
    //所有键值
    private $table_key = array(
    );    
    
    //模糊查询字段
    public $fuzzy_query = 'username';
    
    
    
    /**
     * checkLogin 检测当前用户是否登录
     * @param void
     * $return bool 是否登录
     */
    public function checkLogin() {
        return session('help_user_info') ? true : false;
    }
	
    /**
     * 实例化redis
     *访问量
     */
    public function Redis()
    {
        $redisConfig =  config("redis");
        if($redisConfig["setredis"]){
            $redis = array();
            if(true){
                $redis = new \Redis();
                $redis->pconnect($redisConfig['host'], $redisConfig['port']);
                $redis->auth($redisConfig['password']);
                $redis->select(1);
            }else{
                return 'false';
            }
            return $redis;
        }
    }
    /**
     */
    public function listRedis()
    {
        $redisConfig =  config("redis");
        if($redisConfig["setredis"]){
            $redis = array();
            if(true){
                $redis = new \Redis();
                $redis->pconnect($redisConfig['host'], $redisConfig['port']);
                $redis->auth($redisConfig['password']);
                $redis->select(2);
            }else{
                return 'false';
            }
            return $redis;
        }
    }
    /**
     */
    public function listRedistest()
    {
        $redisConfig =  config("redis");
        if($redisConfig["setredis"]){
            $redis = array();
            if(true){
                $redis = new \Redis();
                $redis->pconnect($redisConfig['host'], $redisConfig['port']);
                $redis->auth($redisConfig['password']);
                $redis->select(3);
            }else{
                return 'false';
            }
            return $redis;
        }
    }
    
}