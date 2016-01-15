<?php
namespace App\Controllers;


use App\Modules\Structures\Classes\MasterController;
use App\Views\System\View404;

class Controller404 extends MasterController {

    public function actionIndex() {
        $view = new View404();
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

} 