<?php
function p($value)
{
    if (is_bool($value)) {
        var_dump($value);
    } else if (is_null($value)) {
        var_dump(NULL);
    } else {
        echo "<pre style='padding: 0 3px 2px;" .
            "font-family: Monaco,Menlo,Consolas,\"Courier New\",monospace;" .
            "font-size: 12px;" .
            "color: #333;" .
            "-webkit-border-radius: 3px;" .
            "-moz-border-radius: 3px;" .
            "border-radius: 3px;'>" . print_r($value, true) . "</pre>";
    }
}

/**
 * file extension
 * @param $file_name
 * @return string
 */
function get_extension($file_name)
{
    $ext = explode('.', $file_name);
    $ext = end($ext);
    return strtolower($ext);
}

/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function toGuidString($mix)
{
    if (is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * 短链生成算法
 */
function shorturl($input)
{
    // 要使用生成 URL 的字符
    $base32 = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
        'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
        'y', 'z', '0', '1', '2', '3', '4', '5'
    );

    $hex = md5($input);
    $hexLen = strlen($hex);
    $subHexLen = $hexLen / 8;
    $output = array();

    for ($i = 0; $i < $subHexLen; $i++) {
        // 把加密字符按照 8 位一组 16 进制与 0x3FFFFFFF 进行位与运算
        $subHex = substr($hex, $i * 8, 8);
        $int = 0x3FFFFFFF & (1 * ('0x' . $subHex));
        $out = '';

        for ($j = 0; $j < 6; $j++) {
            $val = 0x0000001F & $int;
            $out .= $base32[$val];
            $int = $int >> 5;
        }

        $output[] = $out;
    }

    return $output;
}
function PhytoDomain($address)
{
    $splite = explode('/',$_SERVER['REQUEST_URI'],3);
    $str = strstr($address, $splite[1]);
    $arr =explode('/', $str, 2);
    return format_url($arr[1] ,"http://localhost/ImageServer/index.php");
}
/**
 * 删除敏感字符
 * @param $value
 * @return array|string
 */
function stripSlashesDeep($value)
{
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}

/**
 * 创建目录
 * @return [type] [description]
 */
function mkdirsByDate($path)
{
    $year = date('Y');
    $month = date('m');
    $yearpath = $path.'/'.$year;
    $monthpath = $yearpath.'/'.$month;
    if (!file_exists($yearpath)) {
        mkdir($yearpath);
    }
    if (!file_exists($monthpath)) {
        mkdir($monthpath);
    }

    return $monthpath;
}

function format_url($srcurl, $baseurl) {
    $srcinfo = parse_url($srcurl);
    if(isset($srcinfo['scheme'])) {
        return $srcurl;
    }
    $baseinfo = parse_url($baseurl);
    $url = $baseinfo['scheme'].'://'.$baseinfo['host'];
    if(substr($srcinfo['path'], 0, 1) == '/') {
        $path = $srcinfo['path'];
    }else{
        $path = dirname($baseinfo['path']).'/'.$srcinfo['path'];
    }
    $rst = array();
    $path_array = explode('/', $path);
    if(!$path_array[0]) {
        $rst[] = '';
    }
    foreach ($path_array AS $key => $dir) {
        if ($dir == '..') {
            if (end($rst) == '..') {
                $rst[] = '..';
            }elseif(!array_pop($rst)) {
                $rst[] = '..';
            }
        }elseif($dir && $dir != '.') {
            $rst[] = $dir;
        }
    }
    if(!end($path_array)) {
        $rst[] = '';
    }
    $url .= implode('/', $rst);
    return str_replace('\\', '/', $url);
}