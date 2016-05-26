<?php
namespace Lcy\Model;
use Lcy\Lib\Db;
/**
 * 封装的image 业务逻辑类
 */
class Picture {
    private $table = 'im_image';
    private $db = null;

    public function __construct() {
        $this->db = new Db();
        return $this;
    }

    /**
     * [save description]
     * @return [type] [description]
     */
    public function save($aid, $url, $short) {
        $n = $this->db->add($this->table, array(
            'aid' => $aid, 
            'origin_url' => $url, 
            'short_url' => $short[0])
        );
        return $n;
    }
    /**
     * [selectAlbums description]
     * @param  [type] $aid [description]
     * @return [type]      [description]
     */
    public function selectAlbums($aid) {
        $arr = $this->db->fetLimit($this->table, $field = 'origin_url, short_url', 'id DESC', 5, $where = "aid=".$aid);
        return $arr;
    }
    /**
     * delete
     */
    public function deletePic($id) {
        $n = $this->db->delete($this->table, $where ="id = $id");
        return $n;
    }
}
// $db->delete($table, $where ='id=1')
// $db->fetOne($table,$condition = null,$field = '*',$where ='')
// $db->fetAll('im_table',$condition = '',$field = '*',$orderby = '',$limit
//   * = '',$where='')