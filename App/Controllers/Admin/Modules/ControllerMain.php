<?php
namespace App\Controllers\Admin\Modules;


use App\Classes\AdministratorAreaController;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->Frame->bindData('content', 'Общая страница модулей.');
        $this->Frame->render();
    }

} 