<?php
namespace App\Modules\News\Controllers\Admin;


use App\Classes\MasterAdminController;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        echo 'Вот такие новостя';
    }

} 