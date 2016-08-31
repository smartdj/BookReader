<?php

/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 16/8/31
 * Time: 下午2:22
 */
namespace Spider;
include_once "../Base/Enum.php";

class Method extends \Enum {
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

    static function get($url, $headers, $cookiesArray)
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