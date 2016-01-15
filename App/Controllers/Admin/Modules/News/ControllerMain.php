<?php
namespace App\Controllers\Admin\Modules\News;


use App\Classes\MasterAdminProxyController;

class ControllerMain extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 