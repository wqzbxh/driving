<?php 
namespace app\admin\model;
use think\Model;

/**
 * 用户模型
 * @author zhuyong
 * @date 2017-08-31 14:11
 * @version 1.0
 */

class Verify extends Model
{

    /**
     * 验证手机号
     */

    public function verifyTelephone($telephone)
    {
       if(!empty($telephone))
       {
           if(preg_match("/^1[3456789]{1}\d{9}$/",$telephone))
           {
              return true;
           }else if(preg_match("/^0\d{2,3}-?\d{7,8}$/",$telephone))
           {
               return true;
           }else
               {
               return false;
           }
       }else
       {
           return false;
       }
    }


}