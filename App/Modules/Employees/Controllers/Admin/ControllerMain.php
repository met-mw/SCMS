<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\Decorations\ViewLink;
use App\Views\Admin\Entities\Decorations\ViewMailTo;
use App\Views\Admin\Entities\ViewList;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewResponse;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewList();
        $view->menu->addItem('Добавить нового сотрудника', '/admin/modules/employees/registration', 'glyphicon-plus');

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $view->table->caption = $manifest['meta']['alias'];
        $view->table->description = $manifest['meta']['description'];
        $view->table
            ->addAction('Редактирование', '/admin/modules/' . strtolower($this->moduleName) . '/edit/', 'glyphicon-pencil')
            ->addAction('Удалить', '/admin/modules/' . strtolower($this->moduleName) . '/delete/', 'glyphicon-trash', false, ['entity-delete'])
        ;

        $employees = DataSource::factory(Employee::cls());
        $employees->builder()
            ->where('deleted=0')
            ->whereAnd()
            ->where('active=1')
            ->sqlCalcFoundRows();
        $view->table->tableBody->data = $employees->findAll();
        $this->pager->prepare();
        $view->table
            ->addColumn('id', '№')
            ->addColumn('email', 'Email')
            ->addColumn('name', 'Имя')
            ->addColumn('active', 'Активность')
        ;

        $view->table->tableBody->addDecoration('active', new ViewActive('active'));
        $view->table->tableBody->addDecoration('email', new ViewMailTo('email'));
        $this->fillPager($view);

        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs();
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $this->breadcrumbs->setIgnores(['page', 'back_params']);
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

    protected function fillBreadcrumbs() {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Сотрудники', 'employees');
    }

} 