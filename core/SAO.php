<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/8/18
 * Time: 17:10
 */

namespace core;

use core\lib\template as T;

/**
 * Class SAO
 * 框架核心文件
 * @package core
 */
class SAO
{
    public static $map = array();// 路由表

    static public function run()
    {
        $route = new \core\lib\route();
        $controller = $route->controller;
        $action = $route->action;

        $file = APP . '/controller/' . $controller . 'Controller.php';
        if (is_file($file)) {
            require_once $file;
            $class = "\\app\\controller\\" . $controller . "Controller";
            $ctrl = new $class();
            $ctrl->$action();
        } else {
            throw new \Exception('找不到控制器' . $controller);
        }
    }

    static public function autoload($class)
    {
        if (isset($map[$class])) {
            return true;
        } else {
            $class = str_replace('\\', '/', $class);
            $file = ROOT . '/' . $class . '.php';
            if (is_file($file)) {
                include $file;
                self::$map[$class] = $class;
            } else {
                return false;
            }
        }
    }

    public function assign($name, $data)
    {
        T::assign($name, $data);
    }

    public function display($file)
    {
        T::fetch($file);
    }

    public function json($code, $message, $str)
    {
        header('Content-type: application/json');
        echo json_encode(
            array(
                'status' => $code,
                'message' => $message,
                'data' => $str
            )
        );
    }
}