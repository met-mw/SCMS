<?php
namespace App\Controllers\Admin\Modules\Catalogue\Category;


use App\Classes\MasterAdminProxyController;

class ControllerMain extends MasterAdminProxyController {

    public function actionIndex() {
        $this->proxy->execute();
    }

} 