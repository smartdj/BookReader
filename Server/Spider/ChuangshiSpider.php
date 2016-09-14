<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/7
 * Time: 20:39
 */

namespace Spider;
include_once "webRequest.php";
include_once "simple_html_dom.php";
include_once "../Base/Utils.php";
include_once "../Base/SQLTool.php";

class QidianBookModel
{
    public $Id;
    public $URL;
    public $imgURL;
    public $shortDescription;
    public $longDescription;
    public $name;
    public $authorURL;
    public $authorName;
    public $mainCategory;
    public $subCategory;
    public $status;
    public $writtenWords;
}

class ChuangshiSpider
{
    private static $baseURL = "http://chuangshi.qq.com/bk/p/%d.html";

    static function getFullURLWithPageNumber($pageNumber){
        return sprintf(self::$baseURL,$pageNumber);
    }

    public function getContentWithPageNumber($pageNumber){
        $pageURL = self::getFullURLWithPageNumber($pageNumber);

        $result = WebRequest::get($pageURL, WebRequest::genHeaders($pageURL));

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

        $books = $html_dom->find("tr[class!=underline]");
        foreach ($books as $booktr){

            $bookDataModel = new QidianBookModel();

            //获取bookid
            $bookIdElem = $booktr->find("a[data-bid]", 0);
            if($bookIdElem){
                $bookDataModel->Id = $bookIdElem->attr["data-bid"];
                $bookDataModel->URL = $bookIdElem->href;
            }

            //获取小说封面
            $bookImageElem = $booktr->find("div.book-img-box a img", 0);
            if($bookImageElem){
                $bookDataModel->imgURL = $bookImageElem->attr["src"];
            }

            //获取书名
            $bookNameElem = $booktr->find("div h4 a",0);
            if($bookNameElem){
                $bookDataModel->name = $bookNameElem->innertext;
            }

            //获取作者
            $bookAuthorElem = $booktr->find("div p a[class=name]", 0);
            if($bookAuthorElem){
                $bookDataModel->authorName = $bookAuthorElem->innertext;
                $bookDataModel->authorURL = $bookAuthorElem->href;
            }

            //获取小说主分类
            $mainCategoryElem = $booktr->find("div.book-mid-info p.author a[data-aid=qd_B60]",0);
            if($mainCategoryElem){
                $bookDataModel->mainCategory = $mainCategoryElem->innertext;
            }
            //获取小说子分类
            $subCategoryElem = $booktr->find("div.book-mid-info p.author a[data-aid=qd_B61]",0);
            if($mainCategoryElem){
                $bookDataModel->subCategory = $subCategoryElem->innertext;
            }

            //获取小说写作状态
            $statusElem = $booktr->find("div.book-mid-info p.author span",0);
            if($statusElem){
                $bookDataModel->status = $statusElem->innertext;
            }

            //获取小说短简介
            $shortDescriptionElem = $booktr->find("p.intro",0);
            if($shortDescriptionElem){
                $bookDataModel->shortDescription = trim($shortDescriptionElem->innertext);
            }

            //获取小说字数
            $writtenWrodsElem = $booktr->find("p.update span",0);
            if($writtenWrodsElem){
                $bookDataModel->writtenWords = $writtenWrodsElem->innertext;
            }

            $sqlTool=new \SQLTool();
            $dataArray=array("id" => intval($bookDataModel->Id),
                "url" => $bookDataModel->URL,
                "image_url" => $bookDataModel->imgURL,
                "short_description" => $bookDataModel->shortDescription,
                "name" => $bookDataModel->name,
                "author_url" => $bookDataModel->authorURL,
                "author_name" => $bookDataModel->authorName,
                "main_category" => $bookDataModel->mainCategory,
                "sub_category" => $bookDataModel->subCategory,
                "status" => $bookDataModel->status,
                "written_words" => $bookDataModel->writtenWords);

            $result = $sqlTool->insert($dataArray, "QiDianBaseInfo");

            //array_push($booksInfo,$bookDataModel);

        }

        return $booksInfo;
    }
}

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