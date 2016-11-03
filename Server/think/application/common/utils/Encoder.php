<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午3:08
 */

namespace app\common\utils;


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

    static function is_utf8($str){
        $len = strlen($str);
        for($i = 0; $i < $len; $i++){
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c > 247)) return false;
                elseif ($c > 239) $bytes = 4;
                elseif ($c > 223) $bytes = 3;
                elseif ($c > 191) $bytes = 2;
                else return false;
                if (($i + $bytes) > $len) return false;
                while ($bytes > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bytes--;
                }
            }
        }
        return true;
    }

    static function detectEncoding($str){
        return mb_detect_encoding($str, array("ASCII","UTF-8","GB2312","GBK","BIG5"));
    }
}