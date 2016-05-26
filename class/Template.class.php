<?php
namespace Lcy;
/**
 * 模板引擎
 */
class Template {
    private static $_vars;
    private static $_path;
    private static $_prefix;
    private static $_return = false;// 是否直接返回/输出

    private function __construct() {}

    public static function init($path = null) {
        if( isset($path) && ($path!='')){
            self::$_path = APP_ROOT.'tpl'.'/'.$path.'/';
        }
        else{
            self::$_path = APP_ROOT.'tpl'.'/';
        }
        self::$_vars = array(); 
    }
    public static function set_path($path) {
        self::$_path = $path;
    }

    public static function set_prefix($prefix) {
        self::$_prefix = $prefix;
    }

    public static function assign($key, $value = null)
    {   
        if(!isset(self::$_vars)) {
            self::init();
        }
            
        if (is_array($key)) {
            self::$_vars = array_merge(self::$_vars,$key);
        }

        elseif (($key != '') && (isset($value))) {
            self::$_vars[$key] = $value;
        }     
            
    }

    public static function fetch($file) {
         
        if(!isset(self::$_vars)){
            self::init();
        }
        if(count(self::$_vars)>0){
            extract(self::$_vars,EXTR_PREFIX_ALL, self::$_prefix);
            self::$_vars = array(); 
        }

        ob_start();
        ob_implicit_flush(0); // 关闭绝对刷送
        
        include self::$_path.$file ;
        
        $contents = ob_get_clean();

        self::$_path = null;
        $output = preg_replace('!\s+!', ' ', $contents);

        if(self::$_return){
           return $output;
        }else {
            echo $output;
        }
        
    }
}