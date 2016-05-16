<?php
namespace App\Modules\Frames\Classes\Retrievers;


class FramesRetriever
{

    public function getFrames()
    {
        $frames = scandir(SFW_APP_ROOT . 'Frames');
        $frames = array_diff($frames, ['.', '..']);
        $framesDataArray = [];
        foreach ($frames as $frame) {
            $framesDataArray[] = ['name' => $frame];
        }

        return $framesDataArray;
    }

    public function getList() {
        $frames = scandir(SFW_APP_ROOT . 'Frames');
        return array_diff($frames, ['.', '..']);
    }

} 