<?php
namespace App\Controllers\Admin\Modules\Catalogue;


use App\Classes\MasterAdminProxyController;

class ControllerSave extends MasterAdminProxyController {

    public function actionCategory() {
        $this->proxy->execute();
    }

    public function actionItem() {
        $this->proxy->execute();
    }

} 