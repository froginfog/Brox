<?php
namespace Core;

class Validate {
    private $regEx = array(
        'chinese' => '/^[\x{4e00}-\x{9fa5}]+$/u',
        'english' => '/^[A-Za-z]$/',
        'number'  => '/^[0-9]+$/',
        'email'   => '/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/',
        'url'     => '/^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+/',
        'mobile'  => '/^0?(13|14|15|17|18)[0-9]{9}$/',
        'qq'      => '/^[1-9]([0-9]{5,11})$/',
        'zip'     => '/^\d{6}$/',
        'ip'      => '/^(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)$/',
        'id'      => '/^\d{17}[\d|x]|\d{15}$/',
        'date'    => '/^\d{4}(\-|\/|.)\d{1,2}\1\d{1,2}$/',
        'required'=> '/.+/'
    );
    private $returnResult;
    private $result = array();

    public function __construct($returnResult=false){
        $this->returnResult = $returnResult;
    }

    public function addRegEx($arr){
        foreach($arr as $key=>$value){
            $this->regEx[$key] = $value;
        }
    }

    private function doMatch($regexkey, $subject){
        if(array_key_exists(strtolower($regexkey), $this->regEx)){
            if($this->returnResult){
                preg_match_all($this->regEx[$regexkey], $subject, $this->result);
                return $this->result;
            }else{
                return preg_match_all($this->regEx[$regexkey], $subject);
            }
        }else{
            return false;
        }
    }

    public function chinese($subject){
        return $this->doMatch('chinese', $subject);
    }

    public function english($subject){
        return $this->doMatch('english', $subject);
    }

    public function number($subject){
        return $this->doMatch('number', $subject);
    }

    public function email($subject){
        return $this->doMatch('email', $subject);
    }

    public function url($subject){
        return $this->doMatch('url', $subject);
    }

    public function mobile($subject){
        return $this->doMatch('mobile', $subject);
    }

    public function qq($subject){
        return $this->doMatch('qq', $subject);
    }

    public function zip($subject){
        return $this->doMatch('zip', $subject);
    }

    public function ip($subject){
        return $this->doMatch('ip', $subject);
    }

    public function id($subject){
        return $this->doMatch('id', $subject);
    }

    public function date($subject){
        return $this->doMatch('date', $subject);
    }

    public function required($subject){
        return $this->doMatch('required', $subject);
    }

    public function other($regexkey, $subject){
        return $this->doMatch($this->regEx[$regexkey], $subject);
    }
}