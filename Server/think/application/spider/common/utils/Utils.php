<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/4
 * Time: 12:07
 */
namespace app\spider\common\utils;

function showResult($result){
    echo "<TEXTAREA  rows=6 cols=60>";
    echo $result;
    echo "</TEXTAREA>";
    echo "<br/>";
}