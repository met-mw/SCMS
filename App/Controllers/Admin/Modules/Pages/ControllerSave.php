<?php
namespace App\Controllers\Admin\Modules\Pages;


use App\Classes\MasterAdminProxyController;

class ControllerSave extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 