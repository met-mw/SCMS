<?php
namespace App\Controllers\Admin\Modules\Gallery\Item;


use App\Classes\AdministratorAreaProxyController;

class ControllerSave extends AdministratorAreaProxyController
{

    public function actionIndex()
    {
        $this->Proxy->execute();
    }

}