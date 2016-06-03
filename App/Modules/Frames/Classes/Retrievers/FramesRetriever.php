<?php
namespace App\Modules\Frames\Classes\Retrievers;


use SFileSystem\Classes\Directory;

class FramesRetriever
{

    public function getFrames()
    {
        $FramesFiles = $this->getList();
        $framesDataArray = [];
        foreach ($FramesFiles as $FrameFile) {
            $framesDataArray[] = ['name' => $FrameFile->getName()];
        }

        return $framesDataArray;
    }

    public function getList() {
        $FramesDirectory = new Directory(SFW_MODULES_FRAMES);
        $FramesDirectory->scan();

        return $FramesDirectory->getFiles();
    }

} 