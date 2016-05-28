<?php
namespace App\Controllers\Admin\Modules\Structures;


use App\Classes\AdministratorAreaProxyController;

class ControllerDelete extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 