<?php
namespace App\Controllers\Admin\Service\ModuleInstaller;


use App\Classes\AdministratorAreaController;
use App\Classes\ModuleInstaller;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $ModuleInstaller = new ModuleInstaller($this->ModuleDirectory);
        $manifests = $ModuleInstaller->findManifests();

        $this->Frame->bindData('content', 'Установщик модулей находится в стадии разработки.');
        $this->Frame->render();
    }

}