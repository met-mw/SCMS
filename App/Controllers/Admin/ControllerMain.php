<?php
namespace App\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Views\Admin\ViewMain;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewMain();
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

} 