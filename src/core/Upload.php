<?php
namespace Core;

class Upload {
    private $uploadConfig = array(
        'path'         => '',
        'allowType'    => [],
        'maxSize'      => 0,
        'isRandomName' => true,
    );
    private $originName; //源文件名
    private $tmpName;
    private $final; //最终存入数据库的文件名，多文件上传时是逗号分隔的文件名字符串
    private $fileObject; //从form接收的文件对象

    public function __construct($fieldName){
        $this->fileObject = $_FILES[$fieldName];
    }

    /**
     * 上传配置有默认值，因此可以单独设置其中一项
     * @param array $config
     */
    public function setUp($config){
        foreach($config as $k=>$v){
            if(array_key_exists($k, $this->uploadConfig)){
                $this->uploadConfig[$k] = $v;
            }
        }
    }

    public function doUpload(){
        $this->final = '';
        if(!file_exists(UPLOAD_FOLDER.'/'.$this->uploadConfig['path']) || !is_writable(UPLOAD_FOLDER.'/'.$this->uploadConfig['path'])){
            die($this->getError(100));
        }
        if($this->fileObject['name'] == ''){
            die($this->getError(1));
        }
        $name = $this->fileObject['name'];
        $tmp_name = $this->fileObject['tmp_name'];
        $error = $this->fileObject['error'];
        $size = $this->fileObject['size'];
        if(is_array($name)){
            for($i = 0; $i < count($name); $i++){
                $this->originName = $name[$i];
                if($error[$i] !== UPLOAD_ERR_OK){
                    die($this->getError($error[$i]));
                }
                if($this->uploadConfig['maxSize'] != 0 && $size[$i] > $this->uploadConfig['maxSize']){
                    die($this->getError(99));
                }
                if(!empty($this->uploadConfig['allowType']) && !in_array($this->getExt($this->originName), $this->uploadConfig['allowType'])){
                    die($this->getError(88));
                }
                if(is_uploaded_file($tmp_name[$i])){
                    if($this->uploadConfig['isRandomName']){
                        $this->tmpName = $this->setRandomName($name[$i]);
                    }else{
                        $this->tmpName = $this->originName;
                    }
                    if(move_uploaded_file($tmp_name[$i], UPLOAD_FOLDER.'/'.$this->uploadConfig['path'].'/'.$this->tmpName)){
                        if($this->final == ''){
                            $sep = '';
                        }else{
                            $sep = ',';
                        }
                        $this->final .= $sep.$this->tmpName;
                    }else{
                        die($this->getError(102));
                    }
                }else{
                    die($this->getError(101));
                }
            }
        }else{
            $this->originName = $name;
            if($error !== UPLOAD_ERR_OK){
                die($this->getError($error));
            }
            if($this->uploadConfig['maxSize'] != 0 && $size > $this->uploadConfig['maxSize']){
                die($this->getError(99));
            }
            if(!empty($this->uploadConfig['allowType']) && !in_array($this->getExt($this->originName), $this->uploadConfig['allowType'])){
                die($this->getError(88));
            }
            if(is_uploaded_file($tmp_name)){
                if($this->uploadConfig['isRandomName']){
                    $this->tmpName = $this->setRandomName($name);
                }else{
                    $this->tmpName = $this->originName;
                }
                if(move_uploaded_file($tmp_name, UPLOAD_FOLDER.'/'.$this->uploadConfig['path'].'/'.$this->tmpName)){
                    $this->final = $this->tmpName;
                }else{
                    die($this->getError(102));
                }
            }else{
                die($this->getError(101));
            }
        }
        return true;
    }

    public function uploadForOS(){
        $this->final = '';
        $res = array();
        if($this->fileObject['name'] == ''){
            die($this->getError(1));
        }
        $name = $this->fileObject['name'];
        $tmp_name = $this->fileObject['tmp_name'];
        $error = $this->fileObject['error'];
        $size = $this->fileObject['size'];
        if(is_array($name)){
            for($i = 0; $i < count($name); $i++){
                $this->originName = $name[$i];
                if($error[$i] !== UPLOAD_ERR_OK){
                    die($this->getError($error[$i]));
                }
                if($this->uploadConfig['maxSize'] != '' && $size[$i] > $this->uploadConfig['maxSize']){
                    die($this->getError(99));
                }
                if(!empty($this->uploadConfig['allowType']) && !in_array($this->getExt($name[$i]), $this->uploadConfig['allowType'])){
                    die($this->getError(88));
                }
                if(is_uploaded_file($tmp_name[$i])){
                    if($this->uploadConfig['isRandomName']){
                        $this->tmpName = $this->setRandomName($name[$i]);
                    }else{
                        $this->tmpName = $this->originName;
                    }
                    if($this->final == ''){
                        $sep = '';
                    }else{
                        $sep = ',';
                    }
                    $this->final .= $sep.$this->tmpName;
                    $res[$this->tmpName] = $tmp_name[$i];
                }else{
                    die($this->getError(101));
                }
            }
        }else{
            $this->originName = $name;
            if($error !== UPLOAD_ERR_OK){
                die($this->getError($error));
            }
            if($this->uploadConfig['maxSize'] != '' && $size > $this->uploadConfig['maxSize']){
                die($this->getError(99));
            }
            if(!empty($this->uploadConfig['allowType']) && !in_array($this->getExt($name), $this->uploadConfig['allowType'])){
                die($this->getError(88));
            }
            if(is_uploaded_file($tmp_name)){
                if($this->uploadConfig['isRandomName']){
                    $this->tmpName = $this->setRandomName($name);
                }else{
                    $this->tmpName = $this->originName;
                }
                $this->final = $this->tmpName;
                $res[$this->tmpName] = $tmp_name;
            }else{
                die($this->getError(101));
            }
        }
        return $res;
    }

    public function getFinal(){
        return $this->final;
    }

    private function getExt($fileName){
        $name = explode('.',$fileName);
        return strtolower(end($name));
    }

    private function setRandomName($fileName){
        $ext = $this->getExt($fileName);
        $name = md5(uniqid(microtime(), true));
        return $name.'.'.$ext;
    }

    private function getError($errNum){
        $str = '文件'.$this->originName.'上传出错：';
        switch ($errNum){
            case 1:
                $str .= "上传的文件超过了php.ini中upload_max_filesize选项限制的值";
                break;
            case 2:
                $str .= "上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值";
                break;
            case 3:
                $str .= "文件只有部分被上传";
                break;
            case 4:
                $str .= "没有文件被上传";
                break;
            case 6:
                $str .= "找不到临时文件夹";
                break;
            case 7:
                $str .= "文件写入失败";
                break;
            case 88:
                $str .= "不允许的文件类型";
                break;
            case 99:
                $str .= '文件大小不能超过'.$this->uploadConfig['maxsize'];
                break;
            case 100:
                $str .= "指定的保存路径不存在或不可写";
                break;
            case 101:
                $str .= "非POST方式上传文件";
                break;
            case 102:
                $str .= "上传失败";
                break;
            default:
                $str .= "未知错误";
        }
        return $str;
    }
}