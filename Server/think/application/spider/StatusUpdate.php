<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/4
 * Time: 20:41
 */

namespace Spider;
include_once "../Base/SQLTool.php";

class StatusUpdate
{
    public $spiderName;

    public function spiderRunning($currentPage, $maxPage){
        $sqlTool=new \SQLTool();
        if($sqlTool->insert(array("name"=>$this->spiderName
        , "status"=>"运行中"
        , "current_page"=>$currentPage
        , "max_page"=>$maxPage
        , "lastUpdateTime"=>time()), "QiDianBaseInfo") != 1)
        {
            if($sqlTool->update(array("name"=>$this->spiderName
            , "status"=>"运行中"
            , "current_page"=>$currentPage
            , "max_page"=>$maxPage
            , "lastUpdateTime"=>time()), array("name"=>$this->spiderName), "SpiderStatus") != 1)
            {
                echo "爬虫状态更新失败<br/>";
            }
        }
    }

    public function spiderStopped($currentPage, $maxPage){
        $sqlTool=new \SQLTool();
        if($sqlTool->insert(array("name"=>$this->spiderName
            , "status"=>"已结束"
            , "current_page"=>$currentPage
            , "max_page"=>$maxPage
            , "lastUpdateTime"=>time()), "QiDianBaseInfo") != 1)
        {
            if($sqlTool->update(array("name"=>$this->spiderName
            , "status"=>"已结束"
            , "current_page"=>$currentPage
            , "max_page"=>$maxPage
            , "lastUpdateTime"=>time()), array("name"=>$this->spiderName), "SpiderStatus") != 1)
            {
                echo "爬虫状态更新失败<br/>";
            }
        }
    }
}