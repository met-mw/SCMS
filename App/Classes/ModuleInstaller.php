<?php
namespace App\Classes;


use Exception;

class ModuleInstaller {

    protected $moduleName;

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    public function install($moduleName = null)
    {
        $modulePath = SFW_MODULES_ROOT . $moduleName . DIRECTORY_SEPARATOR;
        $manifest = $this->getManifest($moduleName);

        // TODO: Реализовать установку модуля.
    }

    public function useMigration($moduleName = null)
    {

    }

    public function getManifest($moduleName = null)
    {
        $filePath = SFW_MODULES_ROOT . $moduleName . DIRECTORY_SEPARATOR . 'manifest.php';
        if (!file_exists($filePath)) {
            throw new Exception("Манифест модуля \"{$moduleName}\" не обнаружен.");
        }

        return include(SFW_MODULES_ROOT . $moduleName . DIRECTORY_SEPARATOR . 'manifest.php');
    }

} 