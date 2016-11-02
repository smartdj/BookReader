<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/11/2
 * Time: 上午10:13
 */

namespace app\API\model;

use app\common\model\ModelWrap;

class ProfileModel  extends ModelWrap
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'profile';
//    protected $pk = 'id';

    protected $mapFields = [
        // 为混淆字段定义映射
        'id'        =>  'id',
        'userId' => 'user_id',
        'nickName' => 'nick_name',
        'avatorImg' => 'avator_img',
        'mail' => 'mail'
    ];

    public $id;
    public $userId;
    public $nickName;
    public $avatorImg;
    public $mail;
}