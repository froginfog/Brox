<?php
namespace Core;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Router{
    public static function match(){
        if(isset($_SERVER['PATH_INFO'])){
            $url = $_SERVER['PATH_INFO'];
        }elseif(isset($_SERVER['ORIG_PATH_INFO'])){
            $url = $_SERVER['ORIG_PATH_INFO'];
        }else{
            $url = '';
        }
        foreach(BROX_URL_RULER() as $left=>$right){
            $left = str_replace('/', '\/', $left);
            $res = preg_match($left, $url, $match);
            if($res == 1){
                $right = explode('?', $right);
                list($class, $method) = explode('/', $right[0]);
                if(isset($right[1])){
                    $args = explode('&', $right[1]);
                    foreach($args as $arg){
                        $_arg = explode(':', $arg);
                        $_GET[$_arg[0]] = $match[$_arg[1]];
                    }
                }
                if(ACCESS_LOG){
                    $log = new Logger('Access');
                    $log->pushHandler(new StreamHandler('log/access.log', Logger::INFO));
                    $log->addInfo('Visitor Accesss', array('ip'=>$_SERVER['REMOTE_ADDR'], 'url'=>$url));
                }
                $class = 'Controller\\'.$class.'Controller';
                $controller = new $class;
                $controller->$method();
                break;
            }
        }
        if(isset($res) && $res == 0){
            if(ACCESS_LOG){
                $log = new Logger('Access');
                $log->pushHandler(new StreamHandler('log/access.log', Logger::ERROR));
                $log->addError('Visitor Accesss', array('ip'=>$_SERVER['REMOTE_ADDR'], 'url'=>$url));
            }
            http_response_code(404);
        }

    }
}