<?php
namespace Core;

class Cache {
    private $dir;

    private function makeDir($path){
        if(!file_exists($path)){
            if(!mkdir($path, 777)){
                die("文件创建失败");
            }
        }
    }

    private function getFilename($key){
        return $this->dir.'/'.$key.'_'.md5($key.PRIVATE_KEY);
    }

    /**
     * @param string $path 设置目录
     */
    public function setDir($path){
        $this->dir = $path;
        $this->makeDir($path);
    }

    /**
     * @param string $key 用来识别文件名
     * @return bool
     */
    public function read($key){
        $filename = $this->getFilename($key);
        if($data = file_get_contents($filename)){
            $res = unserialize($data);
            $lifetime = $res['createtime'] + $res['lifetime'];
            if($lifetime > time() || is_null($res['lifetime'])){
                return $res['data'];
            }
        }
        return false;
    }

    /**
     * @param  string $key 用来识别文件名
     * @param  mixed $data 要保存的内容
     * @param  int $lifetime 缓存有效时间
     * @return bool
     */
    public function write($key, $data, $lifetime=null){
        $filename = $this->getFilename($key);
        if($handle = fopen($filename, 'w+')){
            $data = serialize(array('data'=>$data, 'createtime'=>time(), 'lifetime'=>$lifetime));
            flock($handle, LOCK_EX);
            $res = fwrite($handle, $data);
            flock($handle, LOCK_UN);
            if($res !== false){
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $key 用来识别文件名,不传则清空目录
     */
    public function delete($key=null){
        if(is_null($key)){
            $handle = opendir($this->dir);
            while (($file = readdir($handle)) !== false){
                if($file != '.' && $file != '..'){
                    $fullPath = $this->dir.'/'.$file;
                    if(is_dir($fullPath)){
                        $this->delete($fullPath);
                    }else{
                        unlink($fullPath);
                    }
                }
            }
            closedir($handle);
        }else{
            unlink($this->getFilename($key));
        }
    }
}