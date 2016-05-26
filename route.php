<?php
use NoahBuscher\Macaw\Macaw;
use Lcy\Action\ImageController;
use Lcy\Action\AlbumController;

// $image = new Image('./public/img/music/demo.png')->checkQuality();

// $image->batchResize('./public/img/thumbs/thumb_%dx%d.jpg', array(
//     array(400, 400, true, true),
//     array(200, 400, true, true),
//     array(400, 200, true, true),
//     array(100, 100, true, true),
// ));

// $image->resize(100, 100, true, true)->show();
// 
/**
 * 路由
 */
Macaw::get('/', function() {
    ImageController::display('index.html');
});

Macaw::get('fuck', function() {
    echo "成功！";
});
/**
 * 上传图片
 */
Macaw::post('post-image-single', function () {
    // 静态调用非静态
    ImageController::upload();
});
/**
 * url 上传
 */
Macaw::post('post-image-url', function () {
    ImageController::uploadUrlImage();
});
/**
 * 删除单张图片
 */
Macaw::post('delete-image-single/(:num)', function ($id) {
    ImageController::deletePic($id);
});
/**
 * 显示一个相册的图片
 */
Macaw::get('show-album-all', function () {
    AlbumController::listAlbum();
});

Macaw::dispatch();