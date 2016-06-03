<?php
namespace App\Controllers\Admin\Modules\Employees\API;


use App\Classes\AdministratorAreaProxyController;

class ControllerList extends AdministratorAreaProxyController
{

    public function actionIndex()
    {
        $this->Proxy->execute();
    }

}