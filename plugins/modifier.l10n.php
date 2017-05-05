<?php
function smarty_modifier_l10n($str){
    if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && L10N) {
        $httpLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        if($httpLang != ''){
            $lang = substr($httpLang, 0, 2);
            if(file_exists(ROOT.'/l10n/'.$lang.'.php')){
                $arr = include (ROOT.'/l10n/'.$lang.'.php');
                if(array_key_exists($str, $arr)){
                    return $arr[$str];
                }else{
                    return $str;
                }
            }else{
                return $str;
            }
        }else{
            return $str;
        }
    }else{
        return $str;
    }

}