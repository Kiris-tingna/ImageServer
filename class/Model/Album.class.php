<?php
namespace Lcy\Model;
use Lcy\Lib\Db;
/**
 * 封装的image 业务逻辑类
 */
class Album {
    private $table = 'im_album';
    private $db = null;

    public function __construct() {
        $this->db = new Db();
        return $this;
    }

    public function selectAll() {
        $arr = $this->db->fetAll($this->table, $field = '*'); 
        return $arr;
    }

    public function selectIds() {
        $arr = $this->db->fetAll($this->table, $field = 'id'); 
        return $arr;
    }
    
    /**
     * delete
     */
    public function delete($id) {
        // $n = $this->db->delete($this->table, $where ='id='.$id);
        return $this;
    }
}
// $db->delete($table, $where ='id=1')
// $db->fetOne($table,$condition = null,$field = '*',$where ='')
// $db->fetAll('im_table',$condition = '',$field = '*',$orderby = '',$limit
//   * = '',$where='')