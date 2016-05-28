<?php
namespace App\Controllers\Admin\Modules\Employees\Authorization;



use App\Classes\AdministratorAreaProxyController;

class ControllerUnauthorize extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }


} 