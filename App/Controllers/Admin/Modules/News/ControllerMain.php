<?php
namespace App\Controllers\Admin\Modules\News;


use App\Classes\AdministratorAreaProxyController;

class ControllerMain extends AdministratorAreaProxyController {

    public function actionIndex() {
        $this->Proxy->execute();
    }

} 