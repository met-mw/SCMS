<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\AdministratorAreaController;

class ControllerUnauthorize extends AdministratorAreaController {

    public function actionIndex() {
        $this->EmployeeAuthentication->signOut();
        header('Location: /admin');
    }

} 