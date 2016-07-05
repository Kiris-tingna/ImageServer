<?php
// Helper functions

/**
 * file extension
 * @param $file_name
 * @return string
 */
function get_extension($file_name){
    $ext = explode('.', $file_name);
    $ext = end($ext);
    return strtolower($ext);
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function toGuidString($mix) {
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
function shorturl($input) {
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
        $int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
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

/**
 * 删除敏感字符
 * @param $value
 * @return array|string
 */
function stripSlashesDeep($value) {
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return $value;
}