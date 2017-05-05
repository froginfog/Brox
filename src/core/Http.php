<?php
namespace Core;

class Http{

    /**
     * @param string $url
     * @param array $parameters
     * @return mixed
     */
    public static function get($url, $parameters=array()){
        return self::request('get', $url, $parameters);
    }

    /**
     * @param  string $url
     * @param array $parameters
     * @return mixed
     */
    public static function post($url, $parameters=array()){
        return self::request('post', $url, $parameters);
    }

    private static function request($method, $url, $params=array()){
        $param = http_build_query($params);
        $ch = curl_init();
        if($method == 'get'){
            $url .= $param == '' ? '' : '?'.$param;
        }elseif ($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        if($res === false){
            echo curl_error($ch);
            curl_close($ch);
            exit();
        }else{
            curl_close($ch);
            return $res;
        }
    }
}