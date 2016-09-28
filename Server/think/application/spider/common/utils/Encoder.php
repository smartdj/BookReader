<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午3:08
 */

namespace app\spider\common\utils;


class Encoder
{
    static function UTF8ToGBK($data){
        return iconv("UTF-8", "gbk//IGNORE", $data);
    }

    static function GBKToUTF8($data){
        return iconv('gbk','UTF-8', $data);
    }

    static function UTF8ToGB2312($data){
        return iconv("UTF-8", "GB2312//IGNORE", $data);
    }
}