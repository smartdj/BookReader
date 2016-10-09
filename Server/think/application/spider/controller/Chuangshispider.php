<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/7
 * Time: 20:39
 */

namespace app\spider\controller;

use app\spider\common\utils\SearchUtils;
use Sunra\PhpSimple\HtmlDomParser;
use app\spider\model\ChuangShiBook;

class Chuangshispider
{
    private static $baseURL = "http://chuangshi.qq.com/bk/p/%d.html";

    public function start(){
        //取消脚本最大时间限制
        //set_time_limit(0);
        header("Content-Type:text/html; charset=utf-8");

        SearchUtils::searchRank("test a", array("testatest", "aaatewst", "bbb", "ddd"));
        return;

        $chuangshispider = new Chuangshispider();
        $startPageNumber = 1;
        $maxPage = $startPageNumber+1;//初始先设置为2,为了让循环能顺利运行下去
        for ($page=$startPageNumber; $page<$maxPage; $page++){

            $html_dom = $chuangshispider->getContentWithPageNumber($page);

            if($page==$startPageNumber){
                $maxPage = $chuangshispider->getMaxPage($html_dom);
                echo $maxPage;
                echo "<br/>";
            }

            $booksInfo = $chuangshispider->getBooksBaseInfo($html_dom);
            break;
            //sleep(1);
            //print_r($booksInfo);
        }
    }

    //分析目录
    public function getTableofContents($bookURL){
        $result = \app\spider\common\base\WebRequest::get($bookURL, \app\spider\common\base\WebRequest::genHeaders($bookURL));
        if($result){
            $html_dom = HtmlDomParser::str_get_html($result);
            if($html_dom){
                $tableOfContentsURLElem = $html_dom->find('a[class=active]',0);//获取目录页的URL
                if($tableOfContentsURLElem){
                    $tableOfContentsURL = $tableOfContentsURLElem->href;
                    if($tableOfContentsURL){
                        $result = \app\spider\common\base\WebRequest::get($tableOfContentsURL, \app\spider\common\base\WebRequest::genHeaders($tableOfContentsURL));
                        if($result){
                            $html_dom = HtmlDomParser::str_get_html($result);
                            if($html_dom){
                                $listElems = $html_dom->find('div[class=index_area]',0)->find('div[class=list]');
                                foreach ($listElems as $listElem){//获取分卷
                                    $juanTitle = $listElem->find('h1',0)->innertext;
                                    $chapterElems = $listElem->find('a');//获取章节
                                    foreach ($chapterElems as $chapterElem){
                                        echo $juanTitle . "  " .$chapterElem->innertext. "  "  . $chapterElem->href . "<br/>";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //根据页码获取小说列表的URL
    static function getFullURLWithPageNumber($pageNumber){
        return sprintf(self::$baseURL,$pageNumber);
    }

    //根据页码获取小说列表的DOM
    public function getContentWithPageNumber($pageNumber){
        $pageURL = self::getFullURLWithPageNumber($pageNumber);

        $result = \app\spider\common\base\WebRequest::get($pageURL, \app\spider\common\base\WebRequest::genHeaders($pageURL));

        $html_dom = HtmlDomParser::str_get_html($result);
        return $html_dom;
    }

    //获取小说列表的最大页数
    public function getMaxPage($html_dom){

        $pageMaxEM = $html_dom->find('div.Pagination em',1);
        //print_r($pageMaxEM);
        if($pageMaxEM){
            $text = $pageMaxEM->innertext;

            return mb_substr($text, 2, mb_strlen($text, "utf-8") - 3, "utf-8");
        }

        return 0;
    }

    //获取小说列表中，小说的基本信息
    public function getBooksBaseInfo($html_dom){
        $booksInfo = array();

        $books = $html_dom->find("tr[!class]");
        foreach ($books as $booktr){

            $bookDataModel = new ChuangShiBook;

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
                        $bookDataModel->main_category = $categoryArray[0];
                        $bookDataModel->sub_category = $categoryArray[1];
                    }
                    else if(count($categoryArray)==1){
                        $bookDataModel->main_category = $categoryArray[0];
                    }
                }
            }

            //获取书名
            $bookNameElem = $booktr->find("td",2)->find("a",0);
            if($bookNameElem){
                $bookDataModel->book_name = $bookNameElem->innertext;

                //获取bookurl
                $bookDataModel->url = $bookNameElem->href;

                //获取bookid
                $URLPaths = explode("/",$bookDataModel->url);
                if($URLPaths && count($URLPaths)>0){
                    $bookId = $URLPaths[count($URLPaths)-1];
                    $bookDataModel->book_id = str_replace(".html", "", $bookId);
                }
            }

            //最新章节
            $lastestChapterElem = $booktr->find("td",2)->find("a",1);
            if($lastestChapterElem){
                $bookDataModel->lastest_chapter_url = $lastestChapterElem->href;
                $bookDataModel->lastest_chapter = $lastestChapterElem->innertext;
            }

            //获取小说字数
            $writtenWrodsElem = $booktr->find("td",3);
            if($writtenWrodsElem){
                $bookDataModel->written_words = $writtenWrodsElem->innertext;
            }

            //获取作者
            $bookAuthorElem = $booktr->find("td",4)->find("a",0);;
            if($bookAuthorElem){
                $bookDataModel->author_name = $bookAuthorElem->innertext;
                $bookDataModel->author_url = $bookAuthorElem->href;
            }

            //最后更新时间
            $lastestUpdateTimeElem = $booktr->find("td",5)->find("span",0);;
            if($lastestUpdateTimeElem){
                $bookDataModel->lastest_update_time = $lastestUpdateTimeElem->innertext;
            }

            $this->getTableofContents($bookDataModel->url);
            break;
            $bookDataModel->save();
        }

        return $booksInfo;
    }
}

