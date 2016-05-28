<?php
namespace App\Classes;


use SFramework\Classes\Router;

class Proxy
{

    /** @var Router */
    protected $Router;

    public function __construct(Router $Router) {
        $this->Router = $Router;
    }

    public function execute()
    {
        $foundedRoutes = [];
        preg_match('/^App\\\\Controllers\\\\(?:Admin\\\\)?Modules\\\\([a-zA-Z0-9_-]+)/', $this->Router->currentControllerName, $foundedRoutes);
        $moduleName = end($foundedRoutes);

        $moduleClassName = str_replace(
            [
                "App\\Controllers\\Modules\\{$moduleName}\\",
                "App\\Controllers\\Admin\\Modules\\{$moduleName}\\",
                "App\\Controllers\\{$moduleName}\\",
                "App\\Controllers\\Admin\\{$moduleName}\\"
            ],
            [
                "App\\Modules\\{$moduleName}\\Controllers\\",
                "App\\Modules\\{$moduleName}\\Controllers\\Admin\\",
                "App\\Modules\\{$moduleName}\\Controllers\\",
                "App\\Modules\\{$moduleName}\\Controllers\\Admin\\"
            ],
            $this->Router->currentControllerName
        );

        /** @var AdministratorAreaController $moduleController */
        $moduleController = new $moduleClassName($moduleName);
        $moduleController->{$this->Router->currentActionName}();
    }

} 