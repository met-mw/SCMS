<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Frames\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewResponse;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Param;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $frame = $this->frame;
        $frame->addCss('/public/assets/js/bower_components/markitup/markitup/skins/markitup/style.css');
        $frame->addCss('/public/assets/js/bower_components/markitup/markitup/sets/default/style.css');

        $frameName = Param::get('name', false)->asString(false);
        $view = new ViewEditForm();
        $view->frameName = $frameName;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Фреймы', '/frames'),
            new Breadcrumb($frameName ? "Редактирование фрейма \"{$frameName}\"" : 'Создание нового фрейма', '')
        ];

        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->breadcrumbs, 1);
        $frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $frame->bindView('content', $view);
        $frame->render();
    }

} 