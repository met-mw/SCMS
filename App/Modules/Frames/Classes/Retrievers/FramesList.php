<?php
namespace App\Modules\Frames\Classes\Retrievers;


class FramesList {

    public function getList() {
        $frames = scandir(SFW_APP_ROOT . 'Frames');
        return array_diff($frames, ['.', '..']);
    }

} 