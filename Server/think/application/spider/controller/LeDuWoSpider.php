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
        $bookName = "刺杀全世界";
        $this->search($bookName, function($relativePath, $tableOfContentURL) use($bookName){
            if($tableOfContentURL){
                $this->readBookOfContent($tableOfContentURL);
            }
        });

    }
    public function search($bookName, callable $listener = null){
        $URL =  "http://m.leduwo.com/modules/article/waps.php";
        //$URL = "http://27.221.30.95/modules/article/waps.php";

        $formData = sprintf("searchkey=%s&searchtype=articlename&submit=",urlencode(Encoder::UTF8ToGBK($bookName)));//ReactPHP使用

        $headersArray = $this::$headersArray;
        $headersArray["Content-Length"] = strlen($formData);

        $webRequest = new WebRequest();
        $webRequest->asyncRequest("POST", $URL, $headersArray, $formData, function ($response) use(&$listener, $webRequest)
        {
            if($response->getStatusCode() == 302 && $response->hasHeader("Location")){//找到小说
                $location = $response->getHeader("Location");//http://m.leduwo.com/xs/96/96330/
                if(count($location) > 0){
                    if(preg_match("/\d+\/\d+/", $location[0], $matches)) {
                        $bookRelativePath = $matches[0];

                        //获取目录
                        $tableOfContentURL = 'http://m.leduwo.com/wapbook/' . $bookRelativePath . "/";

                        call_user_func_array($listener, array("relativePath"=>$bookRelativePath, "tableOfContentURL"=>$tableOfContentURL));
                        return;
                    }
                }

                call_user_func_array($listener, array("relativePath"=>null, "tableOfContentURL"=>null));

            }
            else{//返回多个结果
                exit("返回多个搜索结果");
            }
        });
    }

    public function readBookOfContent($URL)
    {
        //使用倒序排列
        $URL = substr($URL, 0, strlen($URL)-1);//http://m.leduwo.com/wapbook/96/96330/
        $URL .= "_1_1/";//http://m.leduwo.com/wapbook/96/96330_1_1/

        (new WebRequest())->asyncRequest("GET", $URL, $this::$headersArray, null, function ($response) {
            if($response->getStatusCode() == 200){
                $data = Encoder::GBKToUTF8($response->getBody());
                $html_dom = HtmlDomParser::str_get_html($data);
                if($html_dom){
                    $chapterElem = $html_dom->find("ul[class=chapter]", 0);
                    if($chapterElem){
                        $liElems = $chapterElem->find("li");
                        if($liElems){
                            foreach ($liElems as $liElem){
                                $aTagElem = $liElem->find("a", 0);
                                if($aTagElem){
                                    $chapterURL = $aTagElem->href;
                                    $chapterTitle = $aTagElem->plaintext;
                                }
                            }
                        }
                    }
                }

            }

        });
    }
}