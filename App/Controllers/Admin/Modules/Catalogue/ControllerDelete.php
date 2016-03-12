<?php
namespace App\Controllers\Admin\Modules\Catalogue;


use App\Classes\MasterAdminProxyController;

class ControllerDelete extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

    public function actionGroup() {
        $this->proxy->execute();
    }

} 