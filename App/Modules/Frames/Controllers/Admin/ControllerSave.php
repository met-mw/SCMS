<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\MasterAdminController;
use Exception;
use SFramework\Classes\Param;

class ControllerSave extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $frameName = Param::post('frame-name')->asString();
        $frameContent = Param::post('frame-content')->asString();

        $frameFileName = SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR . $frameName;
        file_put_contents($frameFileName, $frameContent);

        if (!Param::post('frame-save', false)->exists()) {
            header("Location: /admin/modules/frames/?response-type=success&response=0");
        } else {
            header("Location: /admin/modules/frames/edit/?name={$frameName}&response-type=success&response=0");
        }
    }

} 