<?php
namespace App\Controllers\Admin\Service\ModuleInstaller;


use App\Classes\AdministratorAreaController;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->Frame->bindData('content', 'Установщик модулей');
        $this->Frame->render();
    }

}