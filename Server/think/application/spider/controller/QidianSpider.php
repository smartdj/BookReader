<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/3
 * Time: 1:16
 */

namespace app\spider\controller;

use app\spider\common\base\WebRequest;
use Sunra\PhpSimple\HtmlDomParser;

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

class QidianSpider
{
    private static $baseURL = "http://a.qidian.com/?size=-1&sign=-1&tag=-1&chanId=-1&subCateId=-1&orderId=&page=%d&month=-1&style=1&action=-1&vip=-1";

    public function test(){
        //测试获取小说目录
        //$this->getTableofContents($bookDataModel->URL);

    }

    public function start(){
        //取消脚本最大时间限制
        set_time_limit(0);

        $startPageNumber = 1;
        $maxPage = $startPageNumber+1;//初始先设置为2,为了让循环能顺利运行下去
        for ($page=$startPageNumber; $page<$maxPage; $page++){

            $html_dom = $this->getContentWithPageNumber($page);

            if($page==$startPageNumber){
                $maxPage = $this->getMaxPage($html_dom);
                echo $maxPage;
                echo "<br/>";
            }

            $booksInfo = $this->getBooksBaseInfo($html_dom);

            //sleep(1);
            //print_r($booksInfo);
            //break;
        }
    }

    static function getFullURLWithPageNumber($pageNumber){
        return sprintf(self::$baseURL,$pageNumber);
    }

    //获取小说目录
    public function getTableofContents($bookURL)
    {
        $result = WebRequest::get($bookURL, WebRequest::genHeaders($bookURL));
        if ($result) {
            $html_dom = HtmlDomParser::str_get_html($result);
            if ($html_dom) {
                $readElem = $html_dom->find("a[stat-type=read]", 0);//查找界面上的"点击阅读"按钮
                if($readElem){
                    //获取目录的入口
                    $tableOfContentURL = $readElem->href;
                    if($tableOfContentURL){
                        $result = WebRequest::get($tableOfContentURL, WebRequest::genHeaders($tableOfContentURL));//获取目录的DOM
                        if ($result) {
                            $html_dom = HtmlDomParser::str_get_html($result);
                            if ($html_dom) {
                                $contentElem = $html_dom->find("div[id=content]", 0);
                                if($contentElem){

                                    $juanTitle = null;

                                    foreach ($contentElem->children() as $childElem){
                                        if($childElem->class == "box_title"){//卷
                                            $juanElem = $childElem->find("div b", 0);
                                            if($juanElem){
                                                foreach ($juanElem->nodes as $elem){//这里需要再次获取子元素,因为<B>里面包含了<A>(书名),而我只是单纯的需要获取卷名,否则的话还需要使用正则
                                                    if($elem->tag == "text"){
                                                        $juanTitle = trim($elem->plaintext);
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        else if($childElem->class == "box_cont"){//章节
                                            $chapterTitleElems = $childElem->find("a[itemprop=url]");
                                            foreach ($chapterTitleElems as $elem){
                                                $spanElem = $elem->find("span", 0);
                                                if($spanElem){
                                                    $chapterTitle = trim($spanElem->plaintext);
                                                    $chapterURL = $elem->href;
                                                    echo  $chapterURL . "<br/>";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function getContentWithPageNumber($pageNumber){
        $pageURL = self::getFullURLWithPageNumber($pageNumber);

        $result = WebRequest::get($pageURL, WebRequest::genHeaders($pageURL));
        //showResult($result);
        $html_dom = HtmlDomParser::str_get_html($result);
        return $html_dom;
    }

    public function getMaxPage($html_dom){
        $pageMaxDiv = $html_dom->find('div[data-pageMax]',0);

        if($pageMaxDiv){
            return $pageMaxDiv->attr['data-pagemax'];
        }

        return 0;
    }

    public function getBooksBaseInfo($html_dom){
        $booksInfo = array();

        $books = $html_dom->find("li[data-rankid]");
        foreach ($books as $bookli){

            $bookDataModel = new QidianBookModel();

            //获取bookid
            $bookIdElem = $bookli->find("a[data-bid]", 0);
            if($bookIdElem){
                $bookDataModel->Id = $bookIdElem->attr["data-bid"];
                $bookDataModel->URL = $bookIdElem->href;
            }

            //获取小说封面
            $bookImageElem = $bookli->find("div.book-img-box a img", 0);
            if($bookImageElem){
                $bookDataModel->imgURL = $bookImageElem->attr["src"];
            }

            //获取书名
            $bookNameElem = $bookli->find("div h4 a",0);
            if($bookNameElem){
                $bookDataModel->name = $bookNameElem->innertext;
            }

            //获取作者
            $bookAuthorElem = $bookli->find("div p a[class=name]", 0);
            if($bookAuthorElem){
                $bookDataModel->authorName = $bookAuthorElem->innertext;
                $bookDataModel->authorURL = $bookAuthorElem->href;
            }

            //获取小说主分类
            $mainCategoryElem = $bookli->find("div.book-mid-info p.author a[data-aid=qd_B60]",0);
            if($mainCategoryElem){
                $bookDataModel->mainCategory = $mainCategoryElem->innertext;
            }
            //获取小说子分类
            $subCategoryElem = $bookli->find("div.book-mid-info p.author a[data-aid=qd_B61]",0);
            if($mainCategoryElem){
                $bookDataModel->subCategory = $subCategoryElem->innertext;
            }

            //获取小说写作状态
            $statusElem = $bookli->find("div.book-mid-info p.author span",0);
            if($statusElem){
                $bookDataModel->status = $statusElem->innertext;
            }

            //获取小说短简介
            $shortDescriptionElem = $bookli->find("p.intro",0);
            if($shortDescriptionElem){
                $bookDataModel->shortDescription = trim($shortDescriptionElem->innertext);
            }

            //获取小说字数
            $writtenWrodsElem = $bookli->find("p.update span",0);
            if($writtenWrodsElem){
                $bookDataModel->writtenWords = $writtenWrodsElem->innertext;
            }

//            $sqlTool=new \SQLTool();
//            $dataArray=array("id" => intval($bookDataModel->Id),
//                "url" => $bookDataModel->URL,
//                "image_url" => $bookDataModel->imgURL,
//                "short_description" => $bookDataModel->shortDescription,
//                "name" => $bookDataModel->name,
//                "author_url" => $bookDataModel->authorURL,
//                "author_name" => $bookDataModel->authorName,
//                "main_category" => $bookDataModel->mainCategory,
//                "sub_category" => $bookDataModel->subCategory,
//                "status" => $bookDataModel->status,
//                "written_words" => $bookDataModel->writtenWords);
//
//            $result = $sqlTool->insert($dataArray, "QiDianBaseInfo");

            //array_push($booksInfo,$bookDataModel);

        }

        return $booksInfo;
    }

}



