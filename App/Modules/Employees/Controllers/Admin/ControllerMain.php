<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Classes\Retrievers\EmployeesRetriever;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewDateTime;
use SFramework\Views\DataGrid\ViewEmail;
use SFramework\Views\DataGrid\ViewSwitch;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageNumber = Param::get('employee-page', false)->asInteger(false);
        $itemsPerPage = Param::get('employee-items-per-page', false)->asInteger(false);

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new EmployeesRetriever();
        $dataGrid = new DataGrid('employee', '', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new Item('Добавить нового сотрудника', '/admin/modules/employees/registration/'))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/employees/edit/', 'edit', '', [], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/employees/delete/', 'delete', '', [], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('employee-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Имя', null, ['class' => 'text-center', 'style' => 'width: 250px;'], [], true, Param::get('employee-filter-name', false)->asString(false)))
            ->addHeader(new Header('email', 'Email', new ViewEmail(), ['class' => 'text-center'], [], true, Param::get('employee-filter-email', false)->asString(false)))
            ->addHeader(new Header('created', 'Создан', new ViewDateTime('d.m.Y h:i:s'), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('employee-filter-created', false)->asString(false)))
            ->addHeader(new Header('updated', 'Изменён',  new ViewDateTime('d.m.Y h:i:s'), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('employee-filter-updated', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('employee-filter-active', false)->asString(false)))
        ;

        $structures = $retriever->getEmployees(
            $dataGrid->getFilterConditions(),
            $dataGrid->pagination->getLimit(),
            $dataGrid->pagination->getOffset()
        );

        $dataSet = new ArrayDataSet($structures);
        $dataGrid->addDataSet($dataSet);
        $dataGridView->dataGrid = $dataGrid;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Сотрудники', '/employees')
        ];

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $dataGridView);
        $this->frame->render();
    }

} 