<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/11/2
 * Time: 上午10:13
 */

namespace app\API\model;

use app\common\model\ModelWrap;

class UserModel extends ModelWrap
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'user';

    protected $mapFields = [
        // 为混淆字段定义映射
        'id'        =>  'id',
        'identityType' => 'identity_type',
        'identifier' => 'identifier',
        'credential' => 'credential',
        'registerTime' => 'register_time',
        'loginTime' => 'login_time',
        'token' => 'token'
    ];

    public $id;
    public $identityType;
    public $identifier;
    public $credential;
    public $registerTime;
    public $loginTime;
    public $token;
}