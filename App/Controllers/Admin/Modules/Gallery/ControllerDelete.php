<?php
namespace App\Controllers\Admin\Modules\Gallery;


use App\Classes\MasterAdminProxyController;

class ControllerDelete extends MasterAdminProxyController
{

    public function actionIndex()
    {
        $this->proxy->execute();
    }

}