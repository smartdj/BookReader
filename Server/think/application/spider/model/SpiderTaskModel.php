<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/10/9
 * Time: 下午4:38
 */

namespace app\spider\model;

use app\spider\common\base\Enum;
use think\Model;

class TaskType extends Enum {
    const ChuangshiCategoryTask = "ChuangshiCategoryTask";//获取创世小说目录
    const ChuangshiBookListTask = "ChuangshiBookListTask";//获取创世小说列表
    const QidianCategoryTask = "QidianCategoryTask";//获取起点小说目录
    const QidianBookListTask = "QidianBookListTask";//获取七点小说列表
    //const NUMBER = 1;
    //const __default = self::GET;
}

class SpiderTaskModel extends Model{
    public $type;//TaskType
    public $URL;
}