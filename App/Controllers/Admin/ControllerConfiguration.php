<?php
namespace App\Controllers\Admin;


use App\Classes\AdministratorAreaController;

class ControllerConfiguration extends AdministratorAreaController {

    public function actionIndex() {
        $configRoot = SFW_APP_ROOT . 'Config' . DIRECTORY_SEPARATOR;
        $scannedFolder = scandir($configRoot);
        $scannedFolder = array_diff($scannedFolder, ['.', '..']);
    }

} 