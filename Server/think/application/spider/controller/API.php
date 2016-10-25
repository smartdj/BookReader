<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/25
 * Time: 上午10:55
 */

namespace app\spider\controller;


use app\spider\model\QidianBookModel;
use think\Db;
use think\Request;

class API
{
    public function qidianAllBook($page, $count){
        // 获取当前请求的所有变量（经过过滤）
        //var_dump(Request::instance()->param());

//        $list = Db::table('book_qidian')->page($page, $count)->select();
//        $json_string = json_encode($list);
//        echo $json_string;
        $list = QidianBookModel::all(function($query) use ($count, $page)
        {
            $query->where()->limit($count)->order('id', 'asc');
        });
    }
}