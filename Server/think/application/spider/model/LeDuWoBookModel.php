<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/25
 * Time: 上午10:16
 */

namespace app\spider\model;


class LeDuWoBookModel
{
    protected $table = 'book_leduwo';

    protected $pk = 'id';

    public $id;
    public $srcSiteBookId;//小说原始网站的bookid,例如:起点、创世
    public $srcSite;
    public $relativePath;
}