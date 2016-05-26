<?php
require_once 'autoload.php';    // 自动加载
require_once 'config.php';      // 常量配置
require_once 'class/Functions.class.php'; // 全局工具函数

/* site domain */
define('SITE_DOMAIN', 'http://127.0.0.1');
/* date time */
date_default_timezone_set ('Asia/Shanghai');

/* set debug */
if (APP_DEBUG == true) {
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors','On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors','Off');
}

/* magic quotes */
if ( get_magic_quotes_gpc() ) {
    $_GET = stripSlashesDeep($_GET);
    $_POST = stripSlashesDeep($_POST);
    $_COOKIE = stripSlashesDeep($_COOKIE);
    $_SESSION = stripSlashesDeep($_SESSION);
}

require_once 'route.php';       // 路由