<?php
namespace App\Controllers\Admin\Modules\Frames;


use App\Classes\MasterAdminProxyController;

class ControllerEdit extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 