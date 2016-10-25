<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/24
 * Time: 下午5:22
 */

namespace app\spider\model;

use ReflectionClass;
use ReflectionProperty;
use think\Exception;
use think\Model;

class ModelWrap extends Model
{
    public function save($data = [], $where = [], $sequence = null){
        if($data == null){
            //parent::save();
            $reflectionClass = new ReflectionClass($this);
            $propertys = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

            foreach ($propertys as $property){
                $data[$property->getName()] = $property->getValue($this);
            }
            try{
                parent::save($data);
            }
            catch (Exception $e){
                if($e->getCode() == 10501){
                    parent::save($data, [$this->getPk() => $data[$this->getPk()]]);
                }
                else {
                    throw $e;
                }
            }
        }
        else{
            parent::save($data, $where, $sequence);
        }
    }

    function initialize(){
        //使用模型从数据库查询出结果后初始化时将查询结果赋值给属性。
        if(isset($this->data) && count($this->data) > 0){
            $reflectionClass = new ReflectionClass($this);

            foreach ($this->data as $key=>$value){
                if($reflectionClass->hasProperty($key)){
                    $this->$key = $value;
                }
            }
        }
    }
}