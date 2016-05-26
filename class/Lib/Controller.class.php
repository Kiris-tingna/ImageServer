<?php
namespace Lcy\Lib;
use Lcy\Template;
/**
 * 控制器基类
 */
class Controller
{
    /**
     * 输出视图
     * @param  [type] $tpl [description]
     * @return [type]      [description]
     */
    public static function display ($tpl) {
        Template::fetch($tpl);
    }

    /**
     * $this 可调用方法
     * @param  [type] $tpl [description]
     * @return [type]      [description]
     */
    public function render ($tpl) {
        self::display($tpl);
    }

    /**
     * json 输出
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function json($code, $message, $str){
        header('Content-type: application/json');
        echo json_encode(
            array( 
                'status'  => $code,
                'message' => $message,
                'data'    => $str
            )
        );
    }
}