<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/9/28
 * Time: 下午4:33
 */

return [
    // 服务器地址
    'hostname'    => '127.0.0.1',
    // 数据库名
    'database'    => 'spider',
    'params' => [
        \PDO::ATTR_PERSISTENT   => true,//长连接
        \PDO::ATTR_CASE         => \PDO::CASE_LOWER,//返回小写列名
    ]
];