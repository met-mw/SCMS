<?php
namespace App\Controllers\Admin\Configuration;


use App\Classes\AdministratorAreaController;
use App\Classes\Configurator;
use App\Classes\SCMSNotificationLog;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewConfiguration;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Registry;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        SCMSNotificationLog::instance()->pushError('asdasdas');

        $view = new ViewConfiguration();
        $view->Configurator = new Configurator(Registry::get('config'));

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Конфигурация системы', '')
        ];

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

}