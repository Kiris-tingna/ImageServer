<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/8/19
 * Time: 16:51
 */

namespace app\model;
use \core\lib\model;

class Picture extends model
{
    public $table = 'im_picture';

    public function listOne($aid) {
        $result = $this->select($this->table, array('origin_url','short_url'), array(
            'aid'=> $aid
        ));
        return $result;
    }
    public function listAll($aid) {
        $result = $this->select($this->table, array('origin_url','short_url'), array(
            'aid'=> $aid
        ));
        return $result;
    }
    public function saveOne($aid, $url, $short) {
        return $this->insert($this->table, array(
            'aid' => $aid,
            'origin_url' => $url,
            'short_url' => $short[0]
        ));
    }
    public function delOne($id)
    {
        return $this->delete($this->table, array(
            'id'=>$id
        ));
    }
}