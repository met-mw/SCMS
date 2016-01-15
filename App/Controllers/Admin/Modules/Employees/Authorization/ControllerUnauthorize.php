<?php
namespace App\Controllers\Admin\Modules\Employees\Authorization;



use App\Classes\MasterAdminProxyController;

class ControllerUnauthorize extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }


} 