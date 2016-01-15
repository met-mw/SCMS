<?php
namespace App\Controllers\Admin\Modules\Modules;


use App\Classes\MasterAdminProxyController;

class ControllerShow extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 