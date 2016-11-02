<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/24
 * Time: 下午5:22
 */

namespace app\common\model;

use ReflectionClass;
use ReflectionProperty;
use think\Exception;
use think\Model;

class ModelWrap extends Model
{
    public function save($data = [], $where = [], $sequence = null){
        if($data == null){
            $reflectionClass = new ReflectionClass($this);
            $propertys = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

            if(count($this->mapFields) > 0){
                foreach ($this->mapFields as $propertyName => $field){

                    foreach ($propertys as $property){
                        if($property->getName() == $propertyName){
                            $data[$field] = $property->getValue($this);
                            break;
                        }
                    }
                }
            }
            else{
                foreach ($propertys as $property){
                    $data[$property->getName()] = $property->getValue($this);
                }
            }

            try{
                //设置回调,调用类静态方法,更新自增主键
                $eventMethod = $reflectionClass->getMethod("event");
                $eventMethod->invoke(null, 'after_insert', function($self){
                    if($self->isUpdate){
                        $pk = $this->getPk();
                        $pkValue = $this->data[$pk];

                        if($pk && $pkValue){
                            if(count($this->mapFields)){
                                foreach ($this->mapFields as $propertyName => $fieldName){
                                    if($pk == $fieldName){
                                        $this->$pk = $pkValue;
                                        break;
                                    }
                                }
                            }
                            else{
                                $this->$pk = $pkValue;
                            }
                        }
                    }
                });
                return parent::save($data);
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
            return parent::save($data, $where, $sequence);
        }
    }

    function initialize(){
        //使用模型从数据库查询出结果后初始化时将查询结果赋值给属性。
        if(isset($this->data) && count($this->data) > 0){
            $reflectionClass = new ReflectionClass($this);

            foreach ($this->data as $key=>$value){
                if(count($this->mapFields) > 0){
                    $isSetted = false;
                    foreach ($this->mapFields as $propertyName => $fieldName){
                        if($fieldName == $key){
                            if($reflectionClass->hasProperty($propertyName)){
                                $this->$propertyName = $value;
                                $isSetted = true;
                            }
                            break;
                        }
                    }

                    if($isSetted == false){
                        if($reflectionClass->hasProperty($propertyName)){
                            $this->$propertyName = $value;
                        }
                    }
                }
                else if($reflectionClass->hasProperty($key)){
                    $this->$key = $value;
                }
            }
        }
    }
}