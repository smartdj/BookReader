<?php

/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午2:22
 */

namespace app\spider\controller;

use app\spider\common\base\WebRequest;
use app\spider\common\utils\SearchUtils;
use app\spider\common\utils\Encoder;
use Sunra\PhpSimple\HtmlDomParser;

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
        "Accept-Language" =>"zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4",
        "Content-Length" => "0"
    );

    private static $postFieldsArray = array(
        "button" => "SoShu",
        "q" => "0",
        "txt" => ""
    );

    public function test()
    {
        $htmlData = SoshuSpider::search("俗人回档", function ($htmlData){
            $result = SoshuSpider::analyseSearchResultHTML($htmlData);
            $sortedResult = SearchUtils::searchRank("俗人回档", array_keys($result));
            echo $result[$sortedResult[0]]."<br>";
        });
    }

    //执行搜索操作
    static function search($bookName, callable $listener)
    {
        self::$postFieldsArray["txt"] = Encoder::UTF8ToGBK($bookName);

        //$result = WebRequest::post(self::$searchURL, self::$headersArray, self::$postFieldsArray);
        $result = WebRequest::asyncRequest("POST"
            , WebRequest::makePostURL(self::$searchURL, self::$postFieldsArray)
            , self::$headersArray, null, function ($data) use(&$listener)
            {
                $data = Encoder::GBKToUTF8($data);
                call_user_func($listener, $data);
        });
    }

    //解析返回数据
    static function analyseSearchResultHTML($htmlData)
    {
        $html_dom = HtmlDomParser::str_get_html($htmlData);

        $searchResults = $html_dom->find('form p a');

        $resultArray = array();

        foreach ($searchResults as $result){
            if($result->href && $result->plaintext){
                $fullURL = self::$baseURL . $result->href;
                $resultArray[$result->plaintext] = $fullURL;
            }
        }

//        echo "<TEXTAREA  rows=6 cols=60>";
//        echo $htmlData;
//        echo "</TEXTAREA>";

        return $resultArray;
    }
}

