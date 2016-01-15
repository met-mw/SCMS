<?php
namespace App\Modules\Modules\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Models\Module;
use App\Modules\Modules\Views\Admin\ViewModuleShow;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerShow extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->frame->addCss('\public\assets\css\edit-form.css');
        $this->frame->addCss('\public\assets\css\main-menu.css');

        $moduleId = Param::get('pk')->asInteger();

        $view = new ViewModuleShow();
        $module = DataSource::factory(Module::cls(), $moduleId);
        $view->module = $module;

        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

} 