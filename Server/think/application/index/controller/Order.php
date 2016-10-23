<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/10/19
 * Time: 20:37
 */

namespace app\index\controller;

use app\common\Encoder;
use app\common\WebRequest;

class Order
{
    //查单的api接口
    //参考网站
    //http://www.ems-help.com/ems-help/5109/d.htm
    public function Query($no){
        $headers = Array(
            "Connection"=>"keep-alive",
            "Content-Length"=>"41",
            "Pragma"=>"no-cache",
            "Cache-Control"=>"no-cache",
            "Origin"=>"http://www.ecotransite.com",
            "Upgrade-Insecure-Requests"=>"1",
            "User-Agent"=>"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
            "Content-Type"=>"application/x-www-form-urlencoded",
            "Accept"=>"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "DNT"=>"1",
            "Referer"=>"http://www.ecotransite.com/cn-colis.html",
            "Accept-Encoding"=>"gzip, deflate",
            "Accept-Language"=>"zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4"
            //"Cookie"=>"_gat=1; EmsMemberLogin=500022243; _ga=GA1.2.138958059.1474787666"
        );


        $data = sprintf("cp=65001&ntype=1000&cno=%d",$no);//POST    http://www.ems-help.com/ems-help/5108/d.htm
        $data = null;//GET

        $headers["Content-Length"] = strlen($data);

        $URL = "http://www.ecotransite.com/cgi-bin/GInfo.dll?EmsApiTrack";//POST
        $URL = sprintf("http://www.ecotransite.com/cgi-bin/GInfo.dll?EmsApiTrack&cno=%s", $no);//GET

        WebRequest::asyncRequest("GET", $URL, $headers, $data, function ($data){
            echo Encoder::GBKToUTF8($data);
        });
    }

    public function timestamp(){
        WebRequest::asyncRequest("GET", "http://www.ecotransite.com/cgi-bin/EmsData.dll?DoApi", array(), "{\"RequestName\":\"TimeStamp\"}", function ($data){
            echo Encoder::GBKToUTF8($data);
        });
    }


    public function Add(){

    }


}