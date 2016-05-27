<?php
namespace App\Modules\Gallery\Controllers\Admin\Item;


use App\Classes\MasterAdminController;
use App\Modules\Gallery\Models\Admin\Gallery;
use App\Modules\Gallery\Models\Admin\GalleryItem;
use App\Modules\Gallery\Views\Admin\Item\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryItemId = Param::get('id', false)->asInteger(false);
        $galleryId = Param::get('gallery_id')->asInteger(true, 'Недопустимое значение номера галлереи.');

        /** @var GalleryItem $oGalleryItem */
        $oGalleryItem = DataSource::factory(GalleryItem::cls(), $galleryItemId);
        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $galleryId);
        if ($oGalleryItem->isNew() && $oGallery->isNew()) {
            NotificationLog::instance()->pushError('Недопустимое значение параметра!');
            $this->frame->render();

            return;
        }

        $view = new ViewEditForm();
        $view->galleryItem = $oGalleryItem;
        $view->gallery = $oGallery;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Галлереи', '/gallery'),
            new Breadcrumb("Галлерея \"{$oGallery->name}\"", "/item/?gallery_id={$oGallery->id}")
        ];
        if ($oGalleryItem->id !== null) {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb("Редактирование \"{$oGalleryItem->name}\"", '');
        } else {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb('Добавление нового элемента галлереи', '');
        }

        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->breadcrumbs, 1);

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

}