<?php
namespace App\Controllers\Admin\Modules\Frames;



use App\Classes\AdministratorAreaProxyController;

class ControllerMain extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 