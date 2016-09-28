<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/7
 * Time: 20:39
 */

namespace app\spider\controller;

//include_once "../common/base/simple_html_dom.php";

class ChuangShiBookModel
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

class ChuangshiSpider
{
    private static $baseURL = "http://chuangshi.qq.com/bk/p/%d.html";

    public function start(){
        //取消脚本最大时间限制
        //set_time_limit(0);

        $chuangshiSpider = new ChuangshiSpider();
        $startPageNumber = 1;
        $maxPage = $startPageNumber+1;//初始先设置为2,为了让循环能顺利运行下去
        for ($page=$startPageNumber; $page<$maxPage; $page++){

            $html_dom = $chuangshiSpider->getContentWithPageNumber($page);

            if($page==$startPageNumber){
                $maxPage = $chuangshiSpider->getMaxPage($html_dom);
                echo $maxPage;
                echo "<br/>";
            }

            $booksInfo = $chuangshiSpider->getBooksBaseInfo($html_dom);
            break;
            //sleep(1);
            //print_r($booksInfo);
        }
    }
    static function getFullURLWithPageNumber($pageNumber){
        return sprintf(self::$baseURL,$pageNumber);
    }

    public function getContentWithPageNumber($pageNumber){
        $pageURL = self::getFullURLWithPageNumber($pageNumber);

        $result = \app\spider\common\base\WebRequest::get($pageURL, \app\spider\common\base\WebRequest::genHeaders($pageURL));

        $html_dom = str_get_html($result);
        return $html_dom;
    }

    public function getMaxPage($html_dom){

        $pageMaxEM = $html_dom->find('div.Pagination em',1);
        //print_r($pageMaxEM);
        if($pageMaxEM){
            $text = $pageMaxEM->innertext;

            return mb_substr($text, 2, mb_strlen($text, "utf-8") - 3, "utf-8");
        }

        return 0;
    }

    public function getBooksBaseInfo($html_dom){
        $booksInfo = array();

        $books = $html_dom->find("tr[!class]");
        foreach ($books as $booktr){

            $bookDataModel = new ChuangShiBookModel();

            //获取小说分类
            $categoryElem = $booktr->find("td",1)->find("a",0);
            if($categoryElem){
                $categoryStr = $categoryElem->innertext;
                $categoryStr = str_replace("[", "", $categoryStr);
                $categoryStr = str_replace("]", "", $categoryStr);
                $categoryArray = explode("/",$categoryStr);
                if($categoryArray)
                {
                    if(count($categoryArray)==2){
                        $bookDataModel->mainCategory = $categoryArray[0];
                        $bookDataModel->subCategory = $categoryArray[1];
                    }
                    else if(count($categoryArray)==1){
                        $bookDataModel->mainCategory = $categoryArray[0];
                    }
                }
            }

            //获取书名
            $bookNameElem = $booktr->find("td",2)->find("a",0);
            if($bookNameElem){
                $bookDataModel->name = $bookNameElem->innertext;

                //获取bookurl
                $bookDataModel->URL = $bookNameElem->href;

                //获取bookid
                $URLPaths = explode("/",$bookDataModel->URL);
                if($URLPaths && count($URLPaths)>0){
                    $bookId = $URLPaths[count($URLPaths)-1];
                    $bookDataModel->Id = str_replace(".html", "", $bookId);
                }
            }

            //最新章节
            $lastestChapterElem = $booktr->find("td",2)->find("a",1);
            if($lastestChapterElem){
                $bookDataModel->lastestChapterURL = $lastestChapterElem->href;
                $bookDataModel->lastestChapter = $lastestChapterElem->innertext;
            }

            //获取小说字数
            $writtenWrodsElem = $booktr->find("td",3);
            if($writtenWrodsElem){
                $bookDataModel->writtenWords = $writtenWrodsElem->innertext;
            }

            //获取作者
            $bookAuthorElem = $booktr->find("td",4)->find("a",0);;
            if($bookAuthorElem){
                $bookDataModel->authorName = $bookAuthorElem->innertext;
                $bookDataModel->authorURL = $bookAuthorElem->href;
            }

            //最后更新时间
            $lastestUpdateTimeElem = $booktr->find("td",5)->find("span",0);;
            if($lastestUpdateTimeElem){
                $bookDataModel->lastestUpdateTime = $lastestUpdateTimeElem->innertext;
            }

            $sqlTool=new \SQLTool();
            $dataArray=array("id" => intval($bookDataModel->Id),
                "url" => $bookDataModel->URL,
                "cover_image_url" => $bookDataModel->coverImgURL,
                "short_description" => $bookDataModel->shortDescription,
                "name" => $bookDataModel->name,
                "author_url" => $bookDataModel->authorURL,
                "author_name" => $bookDataModel->authorName,
                "main_category" => $bookDataModel->mainCategory,
                "sub_category" => $bookDataModel->subCategory,
                "status" => $bookDataModel->status,
                "written_words" => $bookDataModel->writtenWords,
                "lastest_update_time" => $bookDataModel->lastestUpdateTime,
                "lastest_chapter" => $bookDataModel->lastestChapter,
                "lastest_chapter_url" => $bookDataModel->lastestChapterURL);

            $result = $sqlTool->insert($dataArray, "QiDianBaseInfo");

            //array_push($booksInfo,$bookDataModel);

        }

        return $booksInfo;
    }
}

