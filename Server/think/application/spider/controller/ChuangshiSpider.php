<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/7
 * Time: 20:39
 */

namespace app\spider\controller;

use app\common\network\WebRequest;
use app\spider\model\ChuangShiBook_TableOfContents;
use Sunra\PhpSimple\HtmlDomParser;
use app\spider\model\ChuangShiBook;
use think\Db;

class ChuangshiSpider
{
    private static $baseURL = "http://chuangshi.qq.com/bk/p/%d.html";

    public function start()
    {
        //取消脚本最大时间限制
        //set_time_limit(0);
        header("Content-Type:text/html; charset=utf-8");

        $chuangshispider = new ChuangshiSpider();
        $startPageNumber = 1;
        $maxPage = $startPageNumber + 1;//初始先设置为2,为了让循环能顺利运行下去
        for ($page = $startPageNumber; $page < $maxPage; $page++) {

            $html_dom = $chuangshispider->getContentWithPageNumber($page);

            if ($page == $startPageNumber) {
                $maxPage = $chuangshispider->getMaxPage($html_dom);
            }

            $booksInfo = $chuangshispider->getBooksBaseInfo($html_dom);
            break;
        }
    }

    //获取小说详细信息
    public function getBookDetail($bookId, $bookURL)
    {

        (new WebRequest)->asyncRequest("GET", $bookURL, WebRequest::genHeaders($bookURL), null, function ($response) use ($bookId) {
            if ($response->getStatusCode() == 200) {
                $result = $response->getBody();

                if ($result) {
                    $html_dom = HtmlDomParser::str_get_html($result);
                    if ($html_dom) {
                        $tableOfContentsURLElem = $html_dom->find('a[class=active]', 0);//获取目录页的URL
                        if ($tableOfContentsURLElem) {
                            $tableOfContentsURL = $tableOfContentsURLElem->href;
                            if ($tableOfContentsURL) {
                                $this->getTableofContents($bookId, $tableOfContentsURL);
                            }
                        }

                        $bookModel = ChuangShiBook::get($bookId);
                        //封面
                        $bookCoverImgElem = $html_dom->find('a[class=bookcover]', 0)->find('img', 0);
                        if ($bookCoverImgElem) {
                            $bookModel->cover_img_url = $bookCoverImgElem->src;
                        }

                        //完整描述
                        $descripitionElem = $html_dom->find('div[class=info]', 0);
                        if ($descripitionElem) {
                            $bookModel->long_description = $descripitionElem->innertext;
                        }

                        //连载状态
                        $statusElem = $html_dom->find('div[id=novelInfo]', 0)->find('tr', 4)->find('td', 2)->find('span', 0);
                        if ($statusElem) {
                            $bookModel->book_status = $statusElem->innertext;
                        }

                        $bookModel->save();
                    }
                }
            }
        });
    }

    //获取小说目录
    public function getTableofContents($bookId, $tableOfContentsURL)
    {
        (new WebRequest)->asyncRequest("GET", $tableOfContentsURL, WebRequest::genHeaders($tableOfContentsURL), null, function ($response) use ($bookId) {
            if ($response->getStatusCode() == 200) {
                $result = $response->getBody();
                $html_dom = HtmlDomParser::str_get_html($result);
                if ($html_dom) {
                    $listElems = $html_dom->find('div[class=index_area]', 0)->find('div[class=list]');
                    foreach ($listElems as $listElem) {//获取分卷
                        $juanTitle = $listElem->find('h1', 0)->innertext;
                        $chapterElems = $listElem->find('a');//获取章节


//方法一:一次性插入多条数据(会出现内存消耗过大的问题)
//                        $data = array();
//
//                        foreach ($listElems as $listElem) {//获取分卷
//                            $juanTitle = $listElem->find('h1', 0)->innertext;
//                            $chapterElems = $listElem->find('a');//获取章节
//
//                            foreach ($chapterElems as $chapterElem) {
//                                $chapter["book_id"] = $bookId;
//                                $chapter["juan_title"] = $juanTitle;
//                                $chapter["chapter_title"] = $chapterElem->find("b", 0)->innertext;
//                                $chapter["chapter_url"] = $chapterElem->href;
//                                $chapter["update_time"] = $chapterElem->title;
//                                if (preg_match("/\d+-\d+-\d+ \d+:\d+:\d+/", $chapter["update_time"], $matches)) {
//                                    $chapter["update_time"] = $matches[0];
//                                }
//
//                                array_push($data, $chapter);
//                            }
//                        }
//
//                        //使用事务插入数据避免频繁写入导致缓慢
//                        Db::transaction(function () use ($data, $bookId) {
//                            Db::table('table_of_contnet_chuangshi')->where('book_id', $bookId)->delete();//先删除所有数据
//                            Db::table('table_of_contnet_chuangshi')->insertAll($data);//再把所有章节一次性插入
//                        });

//方法二:循环写入数据
                        foreach ($chapterElems as $chapterElem) {
                            Db::transaction(function () use ($chapterElem, $bookId, $juanTitle) {
                                $data = array();
                                $data["book_id"] = $bookId;
                                $data["juan_title"] = $juanTitle;
                                $data["chapter_title"] = $chapterElem->find("b", 0)->innertext;
                                $data["chapter_url"] = $chapterElem->href;
                                $data["update_time"] = $chapterElem->title;
                                if (preg_match("/\d+-\d+-\d+ \d+:\d+:\d+/", $data["update_time"], $matches)) {
                                    $data["update_time"] = $matches[0];
                                }

                                $oldData = Db::table('table_of_contnet_chuangshi')->where('chapter_url', $data["chapter_url"])->find();
                                if ($oldData) {
                                    Db::table('table_of_contnet_chuangshi')
                                        ->where('chapter_url', $data["chapter_url"])
                                        ->update($data);
                                } else {
                                    Db::table('table_of_contnet_chuangshi')->insert($data);
                                }
                            });
                        }
                    }
                }
            }
        });
    }

    //根据页码获取小说列表的URL
    static function getFullURLWithPageNumber($pageNumber)
    {
        return sprintf(self::$baseURL, $pageNumber);
    }

    //根据页码获取小说列表的DOM
    public function getContentWithPageNumber($pageNumber)
    {
        $pageURL = self::getFullURLWithPageNumber($pageNumber);

        $result = WebRequest::get($pageURL, WebRequest::genHeaders($pageURL));

        $html_dom = HtmlDomParser::str_get_html($result);
        return $html_dom;
    }

    //获取小说列表的最大页数
    public function getMaxPage($html_dom)
    {

        $pageMaxEM = $html_dom->find('div.Pagination em', 1);
        //print_r($pageMaxEM);
        if ($pageMaxEM) {
            $text = $pageMaxEM->innertext;

            return mb_substr($text, 2, mb_strlen($text, "utf-8") - 3, "utf-8");
        }

        return 0;
    }

    //获取小说列表中，小说的基本信息
    public function getBooksBaseInfo($html_dom)
    {
        $booksInfo = array();

        $books = $html_dom->find("tr[!class]");
        foreach ($books as $booktr) {

            $bookDataModel = new ChuangShiBook;

            //获取小说分类
            $categoryElem = $booktr->find("td", 1)->find("a", 0);
            if ($categoryElem) {
                $categoryStr = $categoryElem->innertext;
                $categoryStr = str_replace("[", "", $categoryStr);
                $categoryStr = str_replace("]", "", $categoryStr);
                $categoryArray = explode("/", $categoryStr);
                if ($categoryArray) {
                    if (count($categoryArray) == 2) {
                        $bookDataModel->main_category = $categoryArray[0];
                        $bookDataModel->sub_category = $categoryArray[1];
                    } else if (count($categoryArray) == 1) {
                        $bookDataModel->main_category = $categoryArray[0];
                    }
                }
            }

            //获取书名
            $bookNameElem = $booktr->find("td", 2)->find("a", 0);
            if ($bookNameElem) {
                $bookDataModel->book_name = $bookNameElem->innertext;

                //获取bookurl
                $bookDataModel->url = $bookNameElem->href;

                //获取bookid
                $URLPaths = explode("/", $bookDataModel->url);
                if ($URLPaths && count($URLPaths) > 0) {
                    $bookId = $URLPaths[count($URLPaths) - 1];
                    $bookDataModel->id = str_replace(".html", "", $bookId);
                }
            }

            //最新章节
            $lastestChapterElem = $booktr->find("td", 2)->find("a", 1);
            if ($lastestChapterElem) {
                $bookDataModel->lastest_chapter_url = $lastestChapterElem->href;
                $bookDataModel->lastest_chapter = $lastestChapterElem->innertext;
            }

            //获取小说字数
            $writtenWrodsElem = $booktr->find("td", 3);
            if ($writtenWrodsElem) {
                $bookDataModel->written_words = $writtenWrodsElem->innertext;
            }

            //获取作者
            $bookAuthorElem = $booktr->find("td", 4)->find("a", 0);;
            if ($bookAuthorElem) {
                $bookDataModel->author_name = $bookAuthorElem->innertext;
                $bookDataModel->author_url = $bookAuthorElem->href;
            }

            //最后更新时间
            $lastestUpdateTimeElem = $booktr->find("td", 5)->find("span", 0);;
            if ($lastestUpdateTimeElem) {
                $bookDataModel->lastest_update_time = $lastestUpdateTimeElem->innertext;
            }

            $bookDataModel->save();

            $this->getBookDetail($bookDataModel->id, $bookDataModel->url);
        }

        return $booksInfo;
    }
}

