<?php
namespace App\Controllers\Admin\Modules;


use App\Classes\AdministratorAreaController;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        header('Location:/admin');
    }

} 