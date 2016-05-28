<?php
namespace App\Controllers\Admin\Modules\Frames;


use App\Classes\AdministratorAreaProxyController;

class ControllerSave extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 