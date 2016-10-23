<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 2016/10/18
 * Time: 下午1:51
 */

namespace app\spider\controller;
use app\spider\common\utils\Encoder;
use Sunra\PhpSimple\HtmlDomParser;
use app\spider\common\base\WebRequest;
use app\spider\common\utils\SearchUtils;

class LeDuWoSpider
{
    static private $headersArray = array(
//        "Host"=>"m.leduwo.com",
//        "Connection"=>"keep-alive",
//        "Pragma"=>"no-cache",
//        "Cache-Control"=>"no-cache",
//        "Upgrade-Insecure-Requests"=>"1",
        "User-Agent"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36",
        "Accept"=>"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
//        "DNT"=>"1",
//        "Referer"=>"http=>//m.baidu.com/tcredirect?src=http=>//leduwo.com/book/47/47911/23009279.html&mask=3a53f12f21eb3ef80e6977988aae44f5&et=0",
//        "Accept-Encoding"=>"gzip, deflate, sdch",//搜索的时候需要服务器返回文本，因为乐读窝的GZIP压缩有问题，开启这个头之后CURL不能正确解压。
        "Accept-Language"=>"zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4",
        "Content-Length" => "0",
        "Content-Type"=>"application/x-www-form-urlencoded",
//        "Cookie"=>"PHPSESSID=01ug4phi9dokp1d8aofst4pf32; jieqiVisitId=article_articleviews%3D96330; PHPSESSID=01ug4phi9dokp1d8aofst4pf32; jieqiVisitTime=jieqiArticlesearchTime%3D1477224668; CNZZDATA1257043782=924529475-1477218579-%7C1477223979\r\n"
    );

    public function test(){
        $this->search("刺杀全世界", function($htmlData){
            $html_dom = HtmlDomParser::str_get_html($htmlData);
            if($html_dom){
                $tableOfContentBtnElem = $html_dom->find("div.ablum_read span a", 1);
                if($tableOfContentBtnElem){
                    $URL = $tableOfContentBtnElem->href;
                    $this->readBookOfContent($URL);
                }
            }
        });

    }
    public function search($bookName, callable $listener = null){
        $URL =  "http://m.leduwo.com/modules/article/waps.php";
        //$URL = "http://27.221.30.95/modules/article/waps.php";

        if($listener == null){
            $formField = array(//curl使用
                "searchkey"=> Encoder::UTF8ToGBK($bookName),
                "searchtype"=>"articlename",
                "submit"=>""
            );

            $data = WebRequest::post($URL, null, $formField);
            if($data){
                $data = Encoder::GBKToUTF8($data);
            }
            return $data;
        }
        else{
            $formData = sprintf("searchkey=%s&searchtype=articlename&submit=",urlencode(Encoder::UTF8ToGBK($bookName)));//ReactPHP使用

            $headersArray = $this::$headersArray;
            $headersArray["Content-Length"] = strlen($formData);

            (new WebRequest())->asyncRequest("POST", $URL, $headersArray, $formData, function ($data) use(&$listener)
            {
                $data = Encoder::GBKToUTF8($data);
                echo $data;
                call_user_func($listener, $data);
            });
        }
        return null;
    }

    public function readBookOfContent($URL)
    {
        //使用倒序排列
        $URL = substr($URL, 0, strlen($URL)-1);
        $URL .= "_1_1/";

        (new WebRequest())->asyncRequest("GET", $URL, $this::$headersArray, null, function ($data) {
            $data = Encoder::GBKToUTF8($data);
            $html_dom = HtmlDomParser::str_get_html($data);
            $pageTxt = $html_dom->find("pageinput", 0)->parent()->plaintext;
            echo $pageTxt;
        });
    }
}