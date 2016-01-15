<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\ViewList;
use App\Views\Admin\ViewResponse;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewList();
        $view->response = new ViewResponse($this->alertClass, $this->alertHeader, $this->alertText);
        $view->menu->addItem('Добавить', '/admin/modules/employees/add/', 'glyphicon-plus');

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $view->table->caption = $manifest['meta']['alias'];
        $view->table->description = $manifest['meta']['description'];
        $view->table
            ->addAction('Редактирование', '/admin/modules/employees/edit/', 'glyphicon-pencil')
        ;

        $employees = DataSource::factory(Employee::cls());
        $employees->builder()->sqlCalcFoundRows();
        $view->table->tableBody->data = $employees->findAll();
        $this->pager->prepare();
        $view->table
            ->addColumn('id', '№')
            ->addColumn('email', 'Email')
            ->addColumn('name', 'Имя')
            ->addColumn('active', 'Активность')
        ;

        $view->table->tableBody->addDecoration('active', new ViewActive('active'));
        $this->fillPager($view);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

} 