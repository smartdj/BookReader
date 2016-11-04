<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/11/3
 * Time: 下午3:20
 */

namespace app\API\controller;
use app\API\model\CommonResponseModel;
use think\Db;
use think\response\Json;
/**
 * Class Category
 * @package app\API\controller
 * 分类
 */
class Category
{
    function mainCategory(){
        $result = array();
        Db::startTrans();
        try{
            $data =Db::field('main_category')
                ->table('book_chuangshi')
                ->union('SELECT main_category FROM book_qidian')
                ->select();

            foreach ($data as $value){
                array_push($result, $value["main_category"]);
            }

            return Json::create($result, 200);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return json(new CommonResponseModel(1, null, "查询失败"));
    }

    function subCategory($mainCategory){
        $result = array();
        Db::startTrans();
        try{
            $data =Db::field('sub_category')
                ->table('book_chuangshi')
                ->where('main_category', $mainCategory)
                ->union('SELECT sub_category FROM book_qidian where main_category=\''.$mainCategory.'\'')
                ->select();

            foreach ($data as $value){
                array_push($result, $value["sub_category"]);
            }

            return Json::create($result, 200);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return json(new CommonResponseModel(1, null, "查询失败"));
    }

    function all(){
        $mainCategorys = array();
        Db::startTrans();
        try{
            $data =Db::field('main_category')
                ->table('book_chuangshi')
                ->union('SELECT main_category FROM book_qidian')
                ->select();

            foreach ($data as $value){
                array_push($mainCategorys, $value["main_category"]);
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        if($mainCategorys){
            $result = array();

            foreach ($mainCategorys as $mainCategory){
                $result[$mainCategory] = $this->subCategory_internal($mainCategory);
            }

            return json($result);
        }
    }

    protected function subCategory_internal($mainCategory){
        $result = array();
        Db::startTrans();
        try{
            $data =Db::field('sub_category')
                ->table('book_chuangshi')
                ->where('main_category', $mainCategory)
                ->union('SELECT sub_category FROM book_qidian where main_category=\''.$mainCategory.'\'')
                ->select();

            foreach ($data as $value){
                array_push($result, $value["sub_category"]);
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return $result;
    }
}