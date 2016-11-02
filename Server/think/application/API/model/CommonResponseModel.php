<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/11/2
 * Time: 下午4:13
 */

namespace app\API\model;

/*
 * code定义
 * 0:成功
 * 1:失败
*/


class CommonResponseModel
{
    public $code;
    public $additional;
    public $message;

    public function __construct($code = 0, $additional = "", $message = ""){
        $this->code = $code;
        $this->additional = $additional;
        $this->message = $message;
    }
}