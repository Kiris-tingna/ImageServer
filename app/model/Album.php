<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/8/19
 * Time: 14:07
 */

namespace app\model;
use core\lib\model;

class Album extends model
{
    public $table = 'im_album';

    public function lists()
    {
        $return = $this->select($this->table, '*');
        return $return;
    }

    public function getOne($id)
    {
        $return = $this->get($this->table, '*', array(
            'id'=>$id
        ));
        return $return;
    }
    public function setOne($id, $data)
    {
        return $this->update($this->table, $data, array(
            'id'=>$id
        ));
    }
    public function delOne($id)
    {
        return $this->delete($this->table, array(
            'id'=>$id
        ));
    }
}