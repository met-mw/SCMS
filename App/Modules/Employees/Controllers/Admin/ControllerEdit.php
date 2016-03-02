<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Modules\Employees\Views\Admin\ViewEmployeeEdit;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pk = Param::get('pk', true)->asInteger(true, 'Не указан обязательный параметр.');

        /** @var Employee $oEmployee */
        $oEmployee = DataSource::factory(Employee::cls(), $pk);

        $view = new ViewEmployeeEdit();
        $view->employee = $oEmployee;
        $this->frame->bindView('content', $view);

        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs($oEmployee->getPrimaryKey(), $oEmployee->email);
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->render();
    }

    protected function fillBreadcrumbs($employeeId = null, $employeeEmail = null) {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Сотрудники', 'employees');
        $bcEmployees = $bcModules->findChildNodeByPath('employees');
        $bcEmployees->addChildNode('Редактирование', 'edit', true, true);
        $bcEdit = $bcEmployees->findChildNodeByPath('edit');
        $bcEdit->addChildNode("Редактирование \"{$employeeEmail}\"", "pk={$employeeId}", false, false, true);
    }

} 