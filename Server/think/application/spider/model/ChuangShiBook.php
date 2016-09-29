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
    // 设置当前模型对应的完整数据表名称
    protected $table = 'chuang_shi_book';
    protected $pk = 'id';

    public $id;
    public $url;
    public $cover_img_url;
    public $short_description;
    public $long_description;
    public $name;
    public $author_url;
    public $author_name;
    public $main_category;
    public $sub_category;
    public $status;
    public $written_words;
    public $lastest_update_time;
    public $lastest_chapter;
    public $lastest_chapter_url;

    public function save($data = [], $where = [], $sequence = null){
        if($data == null){
            $data = array(
                'id' => $this->id,
                'url' => $this->url,
                'cover_img_url'    => $this->cover_img_url,
                'short_description'   => $this->short_description,
                'long_description' => $this->long_description,
                'name' => $this->name,
                'author_url' => $this->author_url,
                'author_name' => $this->author_name,
                'main_category' => $this->main_category,
                'sub_category' => $this->sub_category,
                'status' => $this->status,
                'written_words' => $this->written_words,
                'lastest_update_time' => $this->lastest_update_time,
                'lastest_chapter' => $this->lastest_chapter,
                'lastest_chapter_url' => $this->lastest_chapter_url,
            );
        }

        if($where == null){
            $existRecord = self::get($data['id']);//先检查数据是否存在
            if($existRecord){//如果存在则设置更新规则更新
                $where['id'] = $data['id'];
            }
        }

        parent::save($data, $where, $sequence);
    }
}