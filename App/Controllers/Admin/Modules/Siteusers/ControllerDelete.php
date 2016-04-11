<?php
namespace App\Controllers\Admin\Modules\Siteusers;



use App\Classes\MasterAdminProxyController;

class ControllerDelete extends MasterAdminProxyController
{

    public function actionIndex()
    {
        $this->proxy->execute();
    }

}