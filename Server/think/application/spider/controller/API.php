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
use think\db\Query;

class API
{
    public function qidianAllBook($page, $count){
        // 获取当前请求的所有变量（经过过滤）
        //var_dump(Request::instance()->param());
//数据库查询方式
//        $list = Db::table('book_qidian')->page($page, $count)->select();
//        $json_string = json_encode($list);
//        echo $json_string;

        //模型查询方式
        $list = QidianBookModel::all(function(\think\db\Query $query) use ($count, $page)
        {
            $query->page($page, $count)->order('id', 'asc');
        });
        echo json_encode($list);
    }
}