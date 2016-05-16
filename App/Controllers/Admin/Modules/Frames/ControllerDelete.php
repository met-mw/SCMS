<?php


namespace App\Controllers\Admin\Modules\Frames;


use App\Classes\MasterAdminProxyController;

class ControllerDelete extends MasterAdminProxyController
{

    public function actionIndex()
    {
        $this->proxy->execute();
    }

}