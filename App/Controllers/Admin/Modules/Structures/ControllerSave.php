<?php
namespace App\Controllers\Admin\Modules\Structures;


use App\Classes\MasterAdminProxyController;

class ControllerSave extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 