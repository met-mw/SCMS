<?php
namespace App\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Views\Admin\ViewMain;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewMain();
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

} 