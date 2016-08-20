<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/8/19
 * Time: 13:57
 */

namespace core\lib;


class model extends \medoo
{
    public function __construct()
    {
        $options = array(
            "database_type"=> "mysql",
            "database_name"=> DB_NAME,
            "server"    =>DB_HOST,
            "username"  =>DB_USER,
            "password"  =>DB_PASS,
            "port"      =>DB_PORT,
            "charset"   =>DB_CHARSET,
        );
        parent::__construct($options);
    }

}