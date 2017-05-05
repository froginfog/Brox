<?php
function smarty_function_url($params){
    global $config;
    if(array_key_exists('anchor', $params) && $params['anchor'] != ''){
        $temp = $params['anchor'];
        unset($params['anchor']);
        $res = $config['ROOT'].implode('/',$params);
        $res .= '#'.$temp;
    }else{
        $res = $config['ROOT'].implode('/',$params);
    }

    return $res;
}