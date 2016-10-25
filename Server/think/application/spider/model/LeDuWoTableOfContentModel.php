<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/25
 * Time: 上午10:17
 */

namespace app\spider\model;

use app\spider\model\ModelWrap;

class LeDuWoTableOfContentModel extends ModelWrap
{
    protected $table = 'table_of_contnet_leduwo';

    protected $pk = 'chapterURL';

    public $id;//外键, book_leduwo
    public $chapterTitle;
    public $chapterURL;
}