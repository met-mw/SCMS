<?php


namespace App\Controllers\Admin\Modules\Employees;


use App\Classes\MasterAdminProxyController;

class ControllerRegistration extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

    public function actionSignup() {
        $this->proxy->execute();
    }

} 