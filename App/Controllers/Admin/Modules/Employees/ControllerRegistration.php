<?php


namespace App\Controllers\Admin\Modules\Employees;


use App\Classes\AdministratorAreaProxyController;

class ControllerRegistration extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

    public function actionSignup() {
        $this->Proxy->execute();
    }

} 