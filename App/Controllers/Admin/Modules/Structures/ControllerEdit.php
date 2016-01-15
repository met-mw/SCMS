<?php
namespace App\Controllers\Admin\Modules\Structures;


use App\Classes\MasterAdminProxyController;

class ControllerEdit extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

    public function actionAjaxModuleConfig() {
        $this->proxy->execute();
    }

} 