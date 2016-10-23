<?php

/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午2:22
 */
namespace app\spider\common\base;

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
    public function asyncRequest($method, $url, $headers, $data, callable $listener, $currentRedirectTimes = 0) {

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
                function ($response) use(&$listener, &$currentRedirectTimes){
                    $body = $response->getBody();
                    $code = $response->getStatusCode();
                    //自己手动处理跳转，因为测试用发现，调用乐读窝搜索返回的数据，guzzle不能良好的处理
                    if($code > 300 && $code < 400 && $response->hasHeader("Location")
                        && $currentRedirectTimes < $this->maxRedirectTimes){

                        $location = $response->getHeader("Location");
                        if($location && count($location)){
                            $currentRedirectTimes += 1;
                            $this->asyncRequest("GET", $location[0], array(), null, $listener, $currentRedirectTimes);//此处没加重定向次数,目前只有给乐读窝使用
                        }
                    }
                    else{
                        call_user_func($listener, $body) ;
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

    /**
     * [multiProcessRequest 多进程发送请求]
     * @param  [字符串] $method [请求类型]
     * @param  [数组,RequestModel] $requestList [请求内容]
     * @return [type]            [description]
     */
    public static function multiProcessRequest($requestList)
    {
        $ch_arr = array();
        $text = array();
        $len = count($requestList);
        $max_size = ($len > 5) ? 5 : $len;//限制最大进程数量为5
        $requestMap = array();
        $mh = curl_multi_init();

        for ($i = 0; $i < $max_size; $i++)
        {
            $requestObject = $requestList[$i];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $requestObject.URL);
            if($requestObject.cookies)
                curl_setopt($ch, CURLOPT_COOKIE, self::genCookie($requestObject.cookies));
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            if($requestObject.method == Medhod::POST)
            {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestObject.$fields);
            }
            $requestMap[$i] = $ch;
            curl_multi_add_handle($mh, $ch);
        }

        $user_arr = array();

        do {
            while (($cme = curl_multi_exec($mh, $active)) == CURLM_CALL_MULTI_PERFORM);

            if ($cme != CURLM_OK) {break;}

            while ($done = curl_multi_info_read($mh))
            {
                $info = curl_getinfo($done['handle']);
                $tmp_result = curl_multi_getcontent($done['handle']);
                $error = curl_error($done['handle']);
//                $user_arr[] = array_values(getUserInfo($tmp_result));
                //保证同时有$max_size个请求在处理
                if ($i < sizeof($requestList) && isset($requestList[$i]) && $i < count($requestList))
                {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_URL, $requestObject.URL);
                    if($requestObject.cookies)
                        curl_setopt($ch, CURLOPT_COOKIE, self::genCookie($requestObject.cookies));
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    if($requestObject.method == Medhod::POST)
                    {
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestObject.$fields);
                    }
                    $requestMap[$i] = $ch;
                    curl_multi_add_handle($mh, $ch);
                    $i++;
                }
                curl_multi_remove_handle($mh, $done['handle']);
            }
            if ($active)
                curl_multi_select($mh, 10);
        } while ($active);
        curl_multi_close($mh);
        return $user_arr;
    }
}