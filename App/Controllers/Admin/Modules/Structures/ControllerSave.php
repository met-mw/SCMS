<?php
namespace App\Controllers\Admin\Modules\Structures;


use App\Classes\AdministratorAreaProxyController;

class ControllerSave extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 