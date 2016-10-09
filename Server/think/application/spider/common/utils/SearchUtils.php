<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/10/2
 * Time: 21:57
 */

namespace app\spider\common\utils;


class SearchUtils
{
    //根据关键字将items中的字符串根据匹配的相似度排序
    static public function searchRank($keyword, $datas){
        if(empty($keyword) || empty($items)){
            return null;
        }
        $pattern = '/[　 \s]/';//分隔关键字，中文全角空格，半角空格，以及空白字符（这个可能没有用）

        $explodeStrings = preg_split( $pattern, $keyword );

        //过滤掉空白字符串
        $words = array();
        foreach ($explodeStrings as $key){
            if(count($key) > 0){
                array_push($words, $key);
            }
        }

        foreach ($words as $word){
            $maxDist = (strlen($word) - 1) / 2;

            $q = array();

            foreach ($datas as $str){
                if(strlen($word) <= strlen($str)){
                    for ($i=0; $i<$maxDist+1; $i++){
                        for ($j=0; $j<max(strlen($str) - strlen($word) - $i +1, 0); $j++){
                            $dist = self::distance($word, substr($str, $j, strlen($word) + $i));

                            if($dist > 0){
                                array_push($q, $str);
                            }
                        }
                    }
                }
            }
            $datas = $q;
        }

        return $datas;
    }

    static public function distance($str1, $str2){
        $n = strlen($str1);
        $m = strlen($str2);

        $c=array();
        for ($row = 0; $row < $n+1; $row++) {
            for ($col = 0; $col < $m+1; $col++) {
                $c[$row][$col] = 0;
            }
        }

        $i=0; $j=0; $x=0; $y=0; $z=0;

        for (; $i<=$n; $i++){
            $c[i][0] = i;
        }

        for ($i=0; i<=$m; $i++){
            $c[0][$i] = $i;
        }

        for ($i=0; $i<n; $i++){
            for ($j=0; $j<$m; $j++){
                $x = $c[$i][$j+1] + 1;
                $y = $c[$i+1][$j] + 1;

                if ($str1[$i] == $str2[$j]){
                    $z = $c[$i][$j];
                }
                else{
                    $z = $c[$i][$j] +1;
                }
                $c[$i+1][$j+1] = min(min($x, $y), $z);
            }
        }
        return $c[$n][$m];
    }
}