<?php

/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午2:22
 */
namespace app\common\network;

use app\common\utils\Enum;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class Method extends Enum {
    const GET = "GET";
    const POST = "POST";
    const NUMBER = 1;
    const __default = self::GET;
}

class RequestModel
{
    public $method;
    public $headers;
    public $cookies;
    public $fields;
    public $URL;

    public function __construct(){
        $method = new Method(Method::GET);
        $URL = "";
    }
}


class WebRequest
{
    public $maxRedirectTimes = 5;
    private $redirectTimes = 0;

    public static function genHeaders($URL){
        $arr = parse_url($URL);
        $host = $arr['host'];
        return array(
            "Origin" => $host,
            "User-Agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36",
            "Content-Type" => "application/x-www-form-urlencoded",
            "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            //"Referer:http://3g.soshu.cc/Book/Search.aspx",
            "Accept-Encoding" => "gzip, deflate",
            "Accept-Language" =>"zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4"
        );
    }


    private static function genCookie($cookieArray)
    {
        $cookie = '';

        $i = 0;
        $count = count($cookieArray);

        foreach ($cookieArray as $key => $value) {
            if ($i != $count)
                $cookie .= $key . '=' . $value . ';';
            else
                $cookie .= $key . '=' . $value;
        }
        return $cookie;
    }

    static function post($url, $headersArray, $fields, $cookiesArray = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($cookiesArray)
            curl_setopt($ch, CURLOPT_COOKIE, self::genCookie($cookiesArray));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);

        return $result;
    }

    static function get($url, $headers, $cookiesArray = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($cookiesArray)
            curl_setopt($ch, CURLOPT_COOKIE, self::genCookie($cookiesArray));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);

        return $result;
    }

    //异步发送请求
    /**
     * @param $method
     * @param $url
     * @param $headers
     * @param $data
     * @param callable $listener
     * @param bool $allowRedirect
     * @param int $currentRedirectTimes
     */
    public function asyncRequest($method, $url, $headers, $data, callable $listener, $allowRedirect = false, $currentRedirectTimes = 0) {

        $clientOption = [
            //'decode_content' => false,
            'headers'=>[
                "User-Agent"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36"
            ],
            'allow_redirects' => false
        ];

        $client = new \GuzzleHttp\Client($clientOption);

        $request = null;

        if($method == "POST"){
            $request = new \GuzzleHttp\Psr7\Request('POST', $url, $headers, $data);
        }
        else if($method == "GET"){
            $request = new \GuzzleHttp\Psr7\Request('GET', $url, $headers);
        }

        $promise = $client->sendAsync($request)
            ->then(
                //response 类型为:\GuzzleHttp\Psr7\Response
                function ($response) use(&$listener, &$currentRedirectTimes, $allowRedirect){
                    //$body = $response->getBody();
                    $code = $response->getStatusCode();
                    //自己手动处理跳转，因为测试用发现，调用乐读窝搜索返回的数据，guzzle不能良好的处理
                    if($allowRedirect && $code > 300 && $code < 400 && $response->hasHeader("Location")
                        && $currentRedirectTimes < $this->maxRedirectTimes){

                        $location = $response->getHeader("Location");
                        if($location && count($location)){
                            $currentRedirectTimes += 1;
                            $this->asyncRequest("GET", $location[0], array(), null, $listener, $currentRedirectTimes);//此处没加重定向次数,目前只有给乐读窝使用
                        }
                    }
                    else{
                        call_user_func($listener, $response) ;
                    }

                    //echo $code.'<br>' . $body;
                    //var_dump($response);
                },
                function ($execption) {
                    echo $execption->getMessage();
                }
            );
        $promise->wait();
    }

    public static function makePostURL($requestURL, $fields){
        $param = null;
        $keys = array_keys($fields);
        foreach ($keys as $key){
            $param = $param . $key . "=" . $fields[$key];
            $param = $param . "&";
        }
        $param = substr($param, 0, strlen($param)-1);
        return $requestURL . "?" . $param;
    }


}