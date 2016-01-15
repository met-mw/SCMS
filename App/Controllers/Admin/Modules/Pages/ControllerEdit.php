<?php
namespace App\Controllers\Admin\Modules\Pages;


use App\Classes\MasterAdminProxyController;

class ControllerEdit extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 