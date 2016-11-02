<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];
use think\Route;

//访问地址由:  http://localhost/bookreader/Server/think/public/spider/api/qidianallbook/page/1/count/10
//变为:       http://localhost/bookreader/Server/think/public/api/qidianallbook/1/10
Route::rule('api/qidianAllBook/:page/:count','spider/api/qidianAllBook');
Route::post('api/user/login/', 'API/User/login');
Route::post('api/user/register/', 'API/User/register');