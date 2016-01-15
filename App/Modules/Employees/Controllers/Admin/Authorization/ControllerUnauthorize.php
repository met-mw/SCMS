<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\MasterAdminController;

class ControllerUnauthorize extends MasterAdminController {

    public function actionIndex() {
        $this->employeeAuthorizator->unauthorize();
        header('Location: /admin');
    }

} 