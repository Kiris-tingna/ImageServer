<?php
namespace Lcy\Action;
use Lcy\Lib\Controller;
use Lcy\Model\Album;
use Lcy\Model\Picture;
use Lcy\Traits\StaticCallTrait;

class AlbumController extends Controller
{
    use StaticCallTrait;

    public function listAlbumAction() {
        $pm = new Picture();
        $am = new Album();
        // albums
        $albums = $am->selectAll();
        // 封面
        // $aids = $am->selectIds();
        // $albums = $pm->selectAlbums(0);
        foreach ($albums as $key => $value) {
            $albums[$key]['albums'] = $pm->selectAlbums($albums[$key]['id']);
        }
        $this->json('200', 'success', $albums);
    }
}