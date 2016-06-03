<?php
namespace App\Classes;


use Exception;
use SFileSystem\Classes\Directory;
use SFileSystem\Interfaces\InterfaceIODirectory;

class ModuleInstaller
{

    /** @var Directory */
    protected $ModulesRoot;

    public function __construct()
    {
        $this->ModulesRoot = new Directory(SFW_MODULES_ROOT);
    }

    public function findModule($moduleName)
    {
        $ModuleDirectory = $this->ModulesRoot->getDirectory($moduleName);
        return $ModuleDirectory->exists() ? $ModuleDirectory : null;
    }

    public function install($moduleName)
    {
        $ModuleDirectory = $this->findModule($moduleName);
        if (!$ModuleDirectory) {
            SCMSNotificationLog::instance()->pushError("Модуль \"{$moduleName}\" не найден.");

            return false;
        }
        $ManifestFile = $this->getManifest($ModuleDirectory);

        // TODO: Реализовать установку модуля.
    }

    public function useMigration($moduleName)
    {

    }

    public function findAllModules()
    {
        $this->ModulesRoot->scan();
        return $this->ModulesRoot->getDirectories();
    }

    public function getManifest(Directory $ModuleDirectory)
    {
        if (!$ModuleDirectory) {
            SCMSNotificationLog::instance()->pushError("Модуль \"{$ModuleDirectory->getName()}\" не найден.");
            return null;
        }

        $ManifestFile = $ModuleDirectory->getFile('manifest.php');
        if (!$ManifestFile || !$ManifestFile->exists()) {
            throw new Exception("Манифест модуля \"{$ModuleDirectory->getName()}\" не найден.");
        }

        return include($ManifestFile->getPath());
    }

    public function findManifests()
    {
        $ModulesDirectories = $this->findAllModules();

        $ManifestsFiles = [];
        foreach ($ModulesDirectories as $ModuleDirectory) {
            $ManifestsFiles[] = $this->getManifest($ModuleDirectory);
        }

        return $ManifestsFiles;
    }

    public function findControllers($moduleFolderName)
    {
        $controllersRoot = SFW_MODULES_ROOT . $moduleFolderName . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'Admin' . DIRECTORY_SEPARATOR;

    }

    protected function scanControllersFolder($controllersFolderName)
    {

    }

    public function createProxyControllers()
    {

    }

}