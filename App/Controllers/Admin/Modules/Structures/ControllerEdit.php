<?php
namespace App\Controllers\Admin\Modules\Structures;


use App\Classes\AdministratorAreaProxyController;

class ControllerEdit extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

    public function actionAjaxModuleConfig() {
        $this->Proxy->execute();
    }

} 