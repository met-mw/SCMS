<?php
namespace App\Controllers\Admin\Modules\Employees;


use App\Classes\MasterAdminProxyController;

class ControllerDelete extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 