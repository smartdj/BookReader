<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/9/28
 * Time: 下午4:41
 */

namespace app\spider\model;

use think\Model;

//小说基本信息模型
class ChuangShiBook  extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'chuang_shi_book';
    protected $pk = 'book_id';

    public $book_id;
    public $url;
    public $cover_img_url;
    public $short_description;
    public $long_description;
    public $book_name;
    public $author_url;
    public $author_name;
    public $main_category;
    public $sub_category;
    public $book_status;
    public $written_words;
    public $lastest_update_time;
    public $lastest_chapter;
    public $lastest_chapter_url;

    public function __construct($data = []){
        //用来将数据库查询结果赋值给成员变量
        foreach ($data as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
        parent::__construct($data);
    }
    public function save($data = [], $where = [], $sequence = null){
        if($data == null){
            $data = array(
                'book_id' => $this->book_id,
                'url' => $this->url,
                'cover_img_url'    => $this->cover_img_url,
                'short_description'   => $this->short_description,
                'long_description' => $this->long_description,
                'book_name' => $this->book_name,
                'author_url' => $this->author_url,
                'author_name' => $this->author_name,
                'main_category' => $this->main_category,
                'sub_category' => $this->sub_category,
                'book_status' => $this->book_status,
                'written_words' => $this->written_words,
                'lastest_update_time' => $this->lastest_update_time,
                'lastest_chapter' => $this->lastest_chapter,
                'lastest_chapter_url' => $this->lastest_chapter_url,
            );
        }

        if($where == null){
            $existRecord = ChuangShiBook::get($data['book_id']);//先检查数据是否存在

            if($existRecord){//如果存在则设置更新规则更新
                echo $existRecord->book_name. "<br/>";
                $where['book_id'] = $existRecord->book_id;
            }
        }

        parent::save($data, $where, $sequence);
    }
}