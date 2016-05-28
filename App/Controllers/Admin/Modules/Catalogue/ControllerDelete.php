<?php
namespace App\Controllers\Admin\Modules\Catalogue;


use App\Classes\AdministratorAreaProxyController;

class ControllerDelete extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

    public function actionGroup() {
        $this->Proxy->execute();
    }

} 