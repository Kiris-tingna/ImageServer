<?php
namespace Lcy\Action;
use Lcy\Lib\Controller;
use Lcy\Model\Picture;
use Lcy\Traits\StaticCallTrait;

class ImageController extends Controller
{
    use StaticCallTrait;
    /**
     * 创建目录
     * @return [type] [description]
     */
    public function mkdirsByDate() {
        $year=date('Y');
        $month=date('m');

        if(!file_exists(ABS_PATH.'/'.UPLOAD_DIR . '/' . $year)){
            mkdir(ABS_PATH.'/'.UPLOAD_DIR . '/' . $year);
        }
        if(!file_exists(ABS_PATH.'/'.UPLOAD_DIR . '/' . $year .'/'. $month)){
            mkdir(ABS_PATH.'/'.UPLOAD_DIR . '/' . $year .'/'. $month);
        }

        return $uploads_dir = ABS_PATH.'/'.UPLOAD_DIR .'/'.$year.'/'.$month;
    }
    /**
     * 获取并移动图片到制定文件夹
     */
    public function uploadAction() {
        $im = new Picture();
        // define site url
        $site = dirname(SITE_DOMAIN.$_SERVER["REQUEST_URI"]);
        // define allowes ext
        $allowed_ext = array('jpg','jpeg','png','gif');
        
        if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
            // 获取files
            $pic = $_FILES['pic'];
            $ext = get_extension($pic['name']);
            $guid = toGuidString($pic['name']);
        
            // 检查ext
            if(!in_array($ext, $allowed_ext )) {
                $this->json('500', 'error', 'Only '.implode(',',$allowed_ext).' files are allowed!');
                return false;
            }

            // 上传到制定upload目录下
            $upload_dir = $this->mkdirsByDate();
            $upload_file = './'.$upload_dir.'/'.$guid.'.'.$ext;

            // 检查是否重复
            if(file_exists($upload_file)) {
                // 物理硬盘上已存在
                $this->json('500', 'error', 'upload failed due to file has exists');
            }else if( move_uploaded_file( $pic['tmp_name'], $upload_file) ) {
                // 持久化存储
                $url = $site.trim($upload_file, '.');
                $short = shorturl($url);
                
                $rows = $im->save(0, $url, $short);
                // 暂时返回源链接
                if ($rows) {
                    $this->json('200', 'success', $url);
                }else {
                    $this->json('500', 'error', 'upload failed due to sql error');
                }
            }else{
                $this->json('500', 'error', 'upload failed due to unknown error');
            }
        }
    }
    /**
     * [uploadUrlImage description]
     * @return [type] [description]
     */
    public function uploadUrlImageAction() {
        $_url = $_POST['url'];
        if(!$_url){
            $this->json('404', 'error', 'no url');
            return false;
        }

        $guid = toGuidString($_url);
        $ext = get_extension($_url);
        $site = dirname(SITE_DOMAIN.$_SERVER["REQUEST_URI"]);

        ob_start();
        // curl 句柄
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_URL, $_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);// 返回的值包含Http头

        curl_exec($ch);
        curl_close($ch);
        $content = ob_get_contents();
        ob_end_clean();

        // 上传到制定upload目录下
        $upload_dir = $this->mkdirsByDate();

        $upload_file = './'.$upload_dir.'/'.$guid.'.'.$ext;

        // 利用相对地址站绝对地址存储
        $this->storeImage($upload_file, $content);
    }
    /**
     * 存储图片
     * @return [type] [description]
     */
    public function storeImage ($relative_url, $content) {
        $im = new Picture();
        // 绝对地址
        $url = dirname(SITE_DOMAIN.$_SERVER["REQUEST_URI"]).trim($relative_url, '.');
        // 短地址
        $short = shorturl($url);
        
        // 检查是否重复
        if(file_exists($relative_url)) {
            // 物理硬盘上已存在
            $this->json('500', 'error-failed due to file has exists', $url);
        }else if( file_put_contents($relative_url, $content) ) {
            // 持久化存储
            $rows = $im->save(0, $url, $short);
            // 暂时返回源链接
            if ($rows) {
                $this->json('200', 'success', $url);
            }else {
                $this->json('500', 'error-upload failed due to sql error', 'none');
            }
        }else{
            $this->json('500', 'error', 'upload failed due to unknown error');
        }
    }
    /**
     * 删除单张图片
     * @return [type] [description]
     */
    public function deletePicAction($id) {
        $im = new Picture();
        $rows = $im->deletePic($id);

        if ($rows){
            $this->json('200', 'success', 'delete success');
        }else{
            $this->json('500', 'error', 'delete failed due to sql error');
        }
    }
}