<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/24
 * Time: 上午9:44
 */

namespace app\spider\model;


use app\spider\model\ModelWrap;

class QidianBookModel extends ModelWrap
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'book_qidian';

    protected $pk = 'id';

    public $id;
    public $URL;
    public $imgURL;
    public $shortDescription;
    public $longDescription;
    public $name;
    public $authorURL;
    public $authorName;
    public $mainCategory;
    public $subCategory;
    public $status;
    public $writtenWords;


}