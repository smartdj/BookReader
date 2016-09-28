<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/9/28
 * Time: 下午4:41
 */

namespace app\spider\model;

use think\Model;

class ChuangShiBook  extends Model
{
    public $Id;
    public $URL;
    public $coverImgURL;
    public $shortDescription;
    public $longDescription;
    public $name;
    public $authorURL;
    public $authorName;
    public $mainCategory;
    public $subCategory;
    public $status;
    public $writtenWords;
    public $lastestUpdateTime;
    public $lastestChapter;
    public $lastestChapterURL;
}