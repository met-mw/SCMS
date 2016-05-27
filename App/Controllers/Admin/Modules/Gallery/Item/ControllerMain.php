<?php
namespace App\Controllers\Admin\Modules\Gallery\Item;


use App\Classes\MasterAdminProxyController;

class ControllerMain extends MasterAdminProxyController
{

    public function actionIndex()
    {
        $this->proxy->execute();
    }

}