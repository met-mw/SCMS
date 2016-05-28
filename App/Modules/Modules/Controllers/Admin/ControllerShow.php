<?php
namespace App\Modules\Modules\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Models\Module;
use App\Modules\Modules\Views\Admin\ViewModuleShow;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerShow extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->Frame->addCss('\public\assets\css\edit-form.css');
        $this->Frame->addCss('\public\assets\css\main-menu.css');

        $moduleId = Param::get('pk')->asInteger();

        $view = new ViewModuleShow();
        $module = DataSource::factory(Module::cls(), $moduleId);
        $view->module = $module;

        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

} 