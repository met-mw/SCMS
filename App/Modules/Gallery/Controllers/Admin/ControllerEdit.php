<?php
namespace App\Modules\Gallery\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryId = Param::get('id', false)->asInteger(false);

        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $galleryId);

        $view = new ViewEditForm();
        $view->gallery = $oGallery;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Галерея', '/gallery')
        ];
        if ($oGallery->id !== null) {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb("Редактирование \"{$oGallery->name}\"", '');
        } else {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb('Добавление новой галлереи', '');
        }

        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->Breadcrumbs, 1);

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

}