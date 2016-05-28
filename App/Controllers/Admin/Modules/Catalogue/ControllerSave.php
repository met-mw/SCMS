<?php
namespace App\Controllers\Admin\Modules\Catalogue;


use App\Classes\AdministratorAreaProxyController;

class ControllerSave extends AdministratorAreaProxyController {

    public function actionCategory() {
        $this->Proxy->execute();
    }

    public function actionItem() {
        $this->Proxy->execute();
    }

} 