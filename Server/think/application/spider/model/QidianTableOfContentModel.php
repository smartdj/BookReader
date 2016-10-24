<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/24
 * Time: 上午10:24
 */

namespace app\spider\model;

use think\Model;
use app\spider\model\ModelWrap;

//toc是table of content的缩写
class QidianTableOfContentModel extends ModelWrap
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'table_of_contnet_qidian';

    protected $pk = 'chapterURL';

    public $bookId;
    public $juanTitle;
    public $chapterTitle;
    public $chapterURL;
    public $updateTime;
}