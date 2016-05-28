<?php
namespace App\Controllers\Admin\Modules\Employees;


use App\Classes\AdministratorAreaProxyController;

class ControllerSave extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 