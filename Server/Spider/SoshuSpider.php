<?php

/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午2:22
 */

namespace Spider;

include_once "webRequest.php";
include_once "../utils/encoder.php";
require '../vendor/autoload.php';
include_once "simple_html_dom.php";

class SoshuSpider
{
    private static $baseURL = "http://3g.soshu.cc/Book/";
    private static $searchURL = "http://3g.soshu.cc/Book/Search.aspx";

    private static $headersArray = array(
        "Origin" => "http://3g.soshu.cc",
        "User-Agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
        "Content-Type" => "application/x-www-form-urlencoded",
        "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Referer:http://3g.soshu.cc/Book/Search.aspx",
        "Accept-Encoding" => "gzip, deflate",
        "Accept-Language" =>"zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4"
    );

    private static $postFieldsArray = array(
        "button" => "SoShu",
        "q" => "0",
        "txt" => ""
    );

    //执行搜索操作
    static function search($bookName)
    {
        self::$postFieldsArray["txt"] = \utils\Encoder::UTF8ToGBK($bookName);

        $result = WebRequest::post(self::$searchURL, self::$headersArray, self::$postFieldsArray);
        return \utils\Encoder::GBKToUTF8($result);
    }



    //解析返回数据
    static function analyseSearchResultHTML($htmlData)
    {
        $html_dom = str_get_html($htmlData);
        // 查找搜索结果中的第一个结果
        $firstSearchResult = $html_dom->find('form p a',0);

        if($firstSearchResult && $firstSearchResult->href){
            $fullURL = self::$baseURL . $firstSearchResult->href;
            echo $fullURL;
        }

//        echo "<TEXTAREA  rows=6 cols=60>";
//        echo $htmlData;
//        echo "</TEXTAREA>";
    }
}

$htmlData = SoshuSpider::search("俗人回档");
SoshuSpider::analyseSearchResultHTML($htmlData);
