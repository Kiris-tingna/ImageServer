<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/8/18
 * Time: 17:21
 */
namespace core\lib;
class route
{
    public $controller;
    public $action;
    public function __construct()
    {
        if(isset($_SERVER['PATH_INFO'])){
            $path = $_SERVER['PATH_INFO'];
            $patharr = explode('/', trim($path, '/'));
            if(isset($patharr[0])){
                $this->controller = $patharr[0];
            }
            unset($patharr[0]);
            if(isset($patharr[1])){
                $this->action = $patharr[1];
                unset($patharr[1]);
            }else{
                $this->action = 'index';
            }

            // url 传参
            $pathcount = count($patharr);
            $i = 0;
            while($i < $pathcount){
                if(isset($patharr[$i + 3])){
                    $_GET[$patharr[$i + 2]] = $patharr[$i + 3];
                }
                $i+=2;
            }
        }else{
            $this->controller = 'index';
            $this->action = 'index';
        }
    }
}