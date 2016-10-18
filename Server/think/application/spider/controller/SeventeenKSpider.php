<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/17
 * Time: 上午9:03
 */

namespace app\spider\controller;

use app\spider\common\base\WebRequest;
use app\spider\model\SeventeenKBook;
use Sunra\PhpSimple\HtmlDomParser;

class SeventeenKSpider
{
    private static $baseURL = "http://all.17k.com/lib/book/%d_0_0_0_0_0_0_1_%d.html";

    public function run(){
        //取消脚本最大时间限制
        //set_time_limit(0);
        header("Content-Type:text/html; charset=utf-8");

        for($category = 2; $category <= 4; $category++){//作品有三个分类(男生、女生、个性)
            $startPageNumber = 1;
            $maxPage = $startPageNumber+1;//初始先设置为2,为了让循环能顺利运行下去
            for ($page=$startPageNumber; $page<$maxPage; $page++){

                $html_dom = $this->getContentWithPageNumber($category, $page);
                var_dump($html_dom);
                break;
                if($page==$startPageNumber){
                    $maxPage = $this->getMaxPage($html_dom);
                    echo $maxPage;
                    echo "<br/>";
                }

                $booksInfo = $this->getBooksBaseInfo($html_dom);
                break;
            }
        }

    }

    //分析目录
    public function getTableofContents($bookURL){
        $result = WebRequest::get($bookURL, WebRequest::genHeaders($bookURL));
        if($result){
            $html_dom = HtmlDomParser::str_get_html($result);
            if($html_dom){
                $tableOfContentsURLElem = $html_dom->find('a[class=active]',0);//获取目录页的URL
                if($tableOfContentsURLElem){
                    $tableOfContentsURL = $tableOfContentsURLElem->href;
                    if($tableOfContentsURL){
                        $result = WebRequest::get($tableOfContentsURL, WebRequest::genHeaders($tableOfContentsURL));
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
    static function getFullURL($cateogry, $pageNumber){
        return sprintf(self::$baseURL, $cateogry, $pageNumber);
    }

    //根据页码获取小说列表的DOM
    public function getContentWithPageNumber($cateogry, $pageNumber){
        $pageURL = self::getFullURL($cateogry, $pageNumber);

        $result = WebRequest::get($pageURL, WebRequest::genHeaders($pageURL));

        $html_dom = HtmlDomParser::str_get_html($result);
        return $html_dom;
    }

    //获取小说列表的最大页数
    public function getMaxPage($html_dom){

        $pageMaxElem = $html_dom->find('div[class=page]',0);
        //print_r($pageMaxEM);
        if($pageMaxElem){
            $text = $pageMaxElem->innertext;
            if(preg_match("/\\d{3,}/", $text, $matches)){
                return mb_substr($text, 2, mb_strlen($text, "utf-8") - 3, "utf-8");
            }
        }
        return 0;
    }

    //获取小说列表中，小说的基本信息
    public function getBooksBaseInfo($html_dom){
        $booksInfo = array();

        $bookElems = $html_dom->find("div[class=alltextlist]");
        foreach ($bookElems as $bookElem){

            $bookDataModel = new SeventeenKBook();

            //获取书名
            $bookNameElem = $bookElem->find("div[class=alltextmiddle] dl dt a",0);
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

            //获取作者
            $bookAuthorElem = $bookElem->find("div[class=alltextmiddle] dl dd ul[class=bq] li span a",0);
            if($bookAuthorElem){
                $bookDataModel->author_name = $bookAuthorElem->innertext;
                $bookDataModel->author_url = $bookAuthorElem->href;
            }

            //获取小说分类
            $categoryElem = $bookElem->find("div[class=alltextmiddle] dl dd ul[class=bq] li span a",1);
            if($categoryElem){
                $categoryStr = $categoryElem->innertext;
                $bookDataModel->main_category;
            }

            //获取小说字数
            $writtenWrodsElem = $bookElem->find("div[class=alltextmiddle] dl dd ul[class=bq] li span code",0);
            if($writtenWrodsElem){
                $bookDataModel->written_words = $writtenWrodsElem->innertext;
            }

            //获取标签
            $tagElems = $bookElem->find("li[class=bq10] p a");
            if($tagElems){
                foreach ($tagElems as $elem){
                    array_push($bookDataModel->tags, $elem->plaintext);
                }
            }

            //简介
            $descriptionElem = $bookElem->find("div[class=alltextmiddle] dl dd ul li", 2)->find("p a", 0);
            if($descriptionElem){
                $bookDataModel->short_description = $descriptionElem->plaintext;
            }

            //最新章节,最后更新时间
            $lastestChapterBlockElem = $bookElem->find("div[class=alltextmiddle] dl dd ul li", 3);
            if($lastestChapterBlockElem){
                $lastestChapterElem = $lastestChapterBlockElem->find("a", 0);
                if($lastestChapterElem){
                    $bookDataModel->lastest_chapter_url = $lastestChapterElem->href;
                    $bookDataModel->lastest_chapter = $lastestChapterElem->innertext;
                }

                $lastestChapterUpdateElem = $lastestChapterBlockElem->find("cite", 0);
                if($lastestChapterUpdateElem){
                    $bookDataModel->lastest_update_time = $lastestChapterUpdateElem->plaintext;
                }
            }

            //获取目录入口
            $startReadElem = $bookElem->find("div[class=alltextright] a", 0);
            if($startReadElem){
                $bookDataModel->bookOfContentURL = $startReadElem->href;
            }

            $this->getTableofContents($bookDataModel->bookOfContentURL);
            break;
            $bookDataModel->save();
        }

        return $booksInfo;
    }

}