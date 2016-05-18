<?php
namespace App\Modules\Gallery\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Gallery\Models\Admin\Gallery;
use App\Modules\Gallery\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController
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
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Галлерея', '/gallery')
        ];
        if ($oGallery->id !== null) {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb("Редактирование \"{$oGallery->name}\"", '');
        } else {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb('Добавление новой галлереи', '');
        }

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

}