<?php
require_once 'vendor/autoload.php';
require_once 'config.php';      // 常量配置
//require_once APP.'/route.php';       // 路由

/* set debug */
if (APP_DEBUG == true) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors','On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors','Off');
}

date_default_timezone_set ('Asia/Shanghai');

/* magic quotes */
if ( get_magic_quotes_gpc() ) {
    $_GET = stripSlashesDeep($_GET);
    $_POST = stripSlashesDeep($_POST);
    $_COOKIE = stripSlashesDeep($_COOKIE);
    $_SESSION = stripSlashesDeep($_SESSION);
}

require_once CORE.'/common/function.php';
require_once CORE.'/SAO.php';
spl_autoload_register('\core\SAO::autoload');// 自动加载

\core\SAO::run();