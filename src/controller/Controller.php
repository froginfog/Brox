<?php
namespace Controller;

use Smarty;
class Controller {
    protected $view;

    public function __construct(){
        $this->view = new Smarty();
        $this->view->left_delimiter = SM_LEFT_DELIMITER;
        $this->view->right_delimiter = SM_RIGHT_DELIMITER;
        $this->view->setTemplateDir(SM_TEMPLATE_DIR);
        $this->view->setCompileDir(SM_COMPILE_DIR);
        $this->view->setCacheDir(SM_CACHE_DIR);
        $this->view->setPluginsDir(SM_PLUGIN_DIR);
        $this->view->caching = SM_CACHEING;
        $this->view->cache_lifetime = SM_CACHE_LIFETIME;
        if(is_array($_SERVER)){
            $r = array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e');
            foreach($_SERVER as $k=>$v){
                $_SERVER[$k] = str_replace($r, '', $v);
            }
        }
        unset($_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);
    }

    protected function jsonReturn($arr){
        header("Content-type: application/json");
        echo json_encode($arr, JSON_HEX_TAG);
    }

    protected function goBack($url=null){
        if(is_null($url)) {
            $url = $_SERVER['HTTP_REFERER'];
            header("location:$url");
        }else{
            global $config;
            /*$start = new router();
            $start -> match($url, $router);*/
            $url = ROOT.$url;
            header("location:$url");
            exit;
        }
    }

    /**
     * 防止csrf 为表单添加token验证
     * 设置token
     * @param string $name token名
     */
    protected function setToken($name=null){
        $tk_name = ($name == null) ? 'token' : $name;
        if(!$_SESSION[$tk_name]){
            $str = substr(md5(uniqid(microtime(), true)), 2, 6);
            $_SESSION[$tk_name] = $str;
        }
    }

    /**
     * 获取token
     * @param string $name token名
     * @return string
     */
    protected function getToken($name=null){
        if($name == null){
            return $_SESSION['token'];
        }else{
            return $_SESSION[$name];
        }
    }
}