<?php
namespace App\Controllers\Admin\Modules\Modules;


use App\Classes\AdministratorAreaProxyController;

class ControllerShow extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 