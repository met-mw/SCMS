<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Pages\Classes\Retrievers\PagesRetriever;
use App\Modules\Pages\Models\Page;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\ViewList;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewSwitch;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageNumber = Param::get('page-page', false)->asInteger(false);
        $itemsPerPage = Param::get('page-items-per-page', false)->asInteger(false);

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new PagesRetriever();
        $dataGrid = new DataGrid('page', '', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new Item('Добавить статическую страницу', '/admin/modules/pages/edit/'))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/pages/edit/', 'edit', '', [], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/pages/delete/', 'delete', '', [], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('page-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Наименование', null, ['style' => 'width: 250px;'], [], true, Param::get('page-filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', null, [], [], true, Param::get('page-filter-description', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('page-filter-active', false)->asString(false)))
        ;

        $structures = $retriever->getPages(
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
            new Breadcrumb('Статичные страницы', '/pages')
        ];

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $dataGridView);
        $this->frame->render();
    }

} 