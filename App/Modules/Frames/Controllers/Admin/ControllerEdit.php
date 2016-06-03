<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Frames\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewResponse;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Param;

class ControllerEdit extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $frame = $this->Frame;
        $frame->addCss('/public/assets/js/bower_components/markitup/markitup/skins/markitup/style.css');
        $frame->addCss('/public/assets/js/bower_components/markitup/markitup/sets/default/style.css');

        $frameName = Param::get('name', false)->asString(false);
        $view = new ViewEditForm();
        $view->frameName = $frameName;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Фреймы', '/modules/frames'),
            new Breadcrumb($frameName ? "Редактирование фрейма \"{$frameName}\"" : 'Создание нового фрейма', '')
        ];

        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->Breadcrumbs, 1);
        $frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $frame->bindView('content', $view);
        $frame->render();
    }

} 