<?php
namespace Lcy\Traits;

trait SingleTonTrait{
    /**
     * 控制器单例
     * @var [type]
     */
    private static $instance = null;
    
    /**
     * 创建一个用来实例化对象的方法
     * @return [type] [description]
     */
    public static function getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance = new self;
        }
        return self::$instance;
    }
}