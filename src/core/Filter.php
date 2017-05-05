<?php
namespace Core;

class Filter {
    public static function filterEscape($value){
        $value = str_replace(array("\0","%00","\r"), '', $value);
        $value = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $value);
        $value = str_replace(array("%3C",'<'), '&lt;', $value);
        $value = str_replace(array("%3E",'>'), '&gt;', $value);
        $value = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $value);
        return $value;
    }

    /**
     * @param mixed $key
     * @param string $type
     * @return mixed
     */
    public static function getOrPost($key, $type){
        if(is_array($key)){
            $res = array();
            foreach($key as $k){
                if($type == 'get'){
                    $res[$k] = self::filterEscape($_GET[$k]);
                }elseif ($type == 'post'){
                    $res[$k] = self::filterEscape($_POST[$k]);
                }
            }
        }else{
            $res = '';
            if($type == 'get'){
                $res[$key] = self::filterEscape($_GET[$key]);
            }elseif($type == 'post'){
                $res[$key] = self::filterEscape($_POST[$key]);
            }
        }
        return $res;
    }
}