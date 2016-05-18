<?php
namespace App\Controllers;


use App\Modules\Structures\Classes\MasterController;
use App\Views\System\View404;

class Controller404 extends MasterController {

    public function actionIndex() {
        (new View404())->render();
    }

} 