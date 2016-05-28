<?php
namespace App\Controllers\Admin\Configuration;


use App\Classes\AdministratorAreaController;
use App\Views\Admin\ViewConfiguration;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $view = new ViewConfiguration();

        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

}