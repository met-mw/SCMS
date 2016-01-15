<?php
namespace App\Controllers\Admin\Modules\Frames;


use App\Classes\MasterAdminProxyController;

class ControllerSave extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 