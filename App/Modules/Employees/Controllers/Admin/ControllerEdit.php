<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Modules\Employees\Views\Admin\ViewEmployeeEdit;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pk = Param::get('id', true)->asInteger(true, 'Не указан обязательный параметр.');

        /** @var Employee $oEmployee */
        $oEmployee = DataSource::factory(Employee::cls(), $pk);

        $view = new ViewEmployeeEdit();
        $view->employee = $oEmployee;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Сотрудники', '/employees'),
            new Breadcrumb("Редактирование \"{$oEmployee->email}\"", '')
        ];

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

} 