<?php
namespace App\Controllers;


use App\Classes\StructureModuleController;
use App\Views\System\View404;

class Controller404 extends StructureModuleController
{

    public function actionIndex()
    {
        (new View404())->render();
    }

} 