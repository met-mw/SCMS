<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageId = Param::get('id', false)->asInteger(false);

        /** @var Page $oPage */
        $oPage = is_null($pageId) ? null : DataSource::factory(Page::cls(), $pageId);

        $view = new ViewEditForm();
        $view->page = $oPage;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Статичные страницы', '/pages')
        ];
        if ($oPage !== null) {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb("Редактирование \"{$oPage->name}\"", '');
        } else {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb('Добавление новой статичной страницы', '');
        }

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

} 