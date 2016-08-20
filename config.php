<?php
// 模式
define('APP_DEBUG', true);

// db config
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'symfony');
define('DB_USER', 'root');
define('DB_PASS', 'admin');
define('DB_CHARSET', 'UTF8');

// 这个必须在主入口设置
define('ROOT', realpath('./'));
define('CORE', ROOT.'/core');
define('APP', ROOT.'/app');

// 静态资源目录
define('STATIC_DIR', ROOT.'/static');
//define('UPLOAD_DIR', APP.'/uploads');
//define('THUMB_DIR', APP.'/thumbs');
define('IMAGE_SERVER', STATIC_DIR.'/uploads');

/* site domain */
//define('SITE_DOMAIN', 'http://127.0.0.1');
/* date time */

// Upload settings
// define('SIZE_LIMIT', '4M');

