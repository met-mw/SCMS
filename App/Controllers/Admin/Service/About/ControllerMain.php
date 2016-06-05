<?php
namespace App\Controllers\Admin\Service\About;


use App\Classes\AdministratorAreaController;
use App\Views\Admin\ViewAbout;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Registry;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->needAuthenticate();

        $view = new ViewAbout();
        $view->credits = Registry::get('credits');

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('О проекте', '')
        ];

        $this->Frame->bindView('content', $view);
        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->render();
    }

}