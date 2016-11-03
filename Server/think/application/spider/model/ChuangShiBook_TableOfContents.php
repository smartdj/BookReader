<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/9/29
 * Time: 下午5:31
 */

namespace app\spider\model;

use app\common\model\ModelWrap;

//目录模型
class ChuangShiBook_TableOfContents extends ModelWrap
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'table_of_contnet_chuangshi';

    public $id;
    public $book_id;
    public $juan_title;
    public $chapter_title;
    public $chapter_url;
    public $update_time;
}