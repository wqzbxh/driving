<?php
namespace app\driving\controller;

class Index
{
    public function index()
    {
        //vendor引入类
       vendor('phpqrcode.phpqrcode');
        //生成二维码
        
       
       //实例化二维码类
       $object = new \QRcode();
       $url = '普秋真，你热不热啊，我这有空调！';
       $level=3;
       $size=4;
        $pathname = 'public' . DS . 'code'  .date('Y-m-d') .DS ;
        if(!is_dir($pathname)) { //若目录不存在则创建之
        mkdir($pathname);
        }
        
        $ad = $pathname  . rand(10000,99999) . ".png";
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
       return  $object->png($url, $ad, $errorCorrectionLevel, $matrixPointSize, 2);
        
    }
}
