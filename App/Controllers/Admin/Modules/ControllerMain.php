<?php
namespace App\Controllers\Admin\Modules;


use App\Classes\MasterAdminController;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->frame->bindData('content', 'Общая страница модулей.');
        $this->frame->render();
    }

} 