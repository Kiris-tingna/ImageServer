<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2016/8/19
 * Time: 13:45
 */

namespace app\controller;
use app\model\Album;
use app\model\Picture;
use core\lib\upload;
use Requests;

class IndexController extends \core\SAO
{
    public function index()
    {
        $this->assign('title', "图床");
        $this->display('index.html');
    }

    public function upload()
    {
        $handle = new upload($_FILES['images']);
        $upload_dir = mkdirsByDate(IMAGE_SERVER);
        if($handle->uploaded)
        {
            $new_name = toGuidString($handle->file_src_name);
            $ext = $handle->file_src_name_ext;
            $handle->file_new_name_body = $new_name;// save uploaded image with a new name
            $handle->image_resize = true;
            $handle->file_overwrite = true;
            $handle->image_x = 500;
            $handle->image_ratio_y = true;
            $handle->process($upload_dir);
            if ($handle->processed) {
                $handle->clean();
                $_url = PhytoDomain($upload_dir.'/'.$new_name.'.'.$ext);
                $pm = new Picture();
                if($pm->saveOne(1, $_url, '')){
                    $this->json('200', 'success', $_url);
                }else{
                    $this->json(500, 'err', "error");
                }

            } else {
                $this->json('500', 'error', "错误");
            }
        }
    }

    /**
     * [uploadUrlImage description]
     * @return [type] [description]
     */
    public function uploadUrl()
    {
        $_url = $_POST['url'];
        if (!$_url) {
            $this->json('404', 'error', 'no url');
            return false;
        }

        $guid = toGuidString($_url);
        $ext = get_extension($_url);
        $response = Requests::get($_url);

        // 上传到制定upload目录下
        $upload_dir = mkdirsByDate(IMAGE_SERVER);
        $upload_file = $upload_dir . '/' . $guid . '.' . $ext;
        file_put_contents($upload_file, $response->body);
        // save 持久化
        $img_long_url = PhytoDomain($upload_file);
        $img_short_url = shorturl($upload_file);// 短地址
        $pm = new Picture();
        if ($pm->saveOne(1, $img_long_url, $img_short_url[1])) {
            $this->json(200,'success', PhytoDomain($upload_file));
        }else{
            $this->json(500,'err', 'error');
        }
    }

    public function listAll() {
        $pm = new Picture();
        $am = new Album();
        // albums
        $albums = $am->lists();
        foreach ($albums as $key => $value) {
            $albums[$key]['albums'] = $pm->listAll($albums[$key]['id']);
        }
        $this->json('200', 'success', $albums);
    }

//    /**
//     * 删除单张图片
//     * @return [type] [description]
//     */
//    public function deletePicAction($id) {
//        $im = new Picture();
//        $rows = $im->deletePic($id);
//
//        if ($rows){
//            $this->json('200', 'success', 'delete success');
//        }else{
//            $this->json('500', 'error', 'delete failed due to sql error');
//        }
//    }
}