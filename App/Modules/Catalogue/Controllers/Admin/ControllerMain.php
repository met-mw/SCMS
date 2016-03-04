<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Classes\Retrievers\CatalogueRetriever;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewPagination;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewSwitch;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $parentCategoryId = Param::get('parent_pk', false);
        if ($parentCategoryId->exists()) {
            $parentCategoryId = $parentCategoryId->asInteger(true, 'Недопустимое значение параметра!');
        } else {
            $parentCategoryId = 0;
        }
        $pageNumber = Param::get('page', false)->asInteger(false);
        $itemsPerPage = Param::get('items-per-page', false)->asInteger(false);

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new CatalogueRetriever();
        $dataGrid = new DataGrid('id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/structures/edit/', 'edit', '', 'Редактировать', ['glyphicon', 'glyphicon-pencil']))
            ->addAction(new Action('id', '/admin/modules/structures/delete/', 'delete', '', 'Удалить', ['glyphicon', 'glyphicon-trash']))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Наименование', null, ['class' => 'text-center', 'style' => 'width: 300px'], [], true, Param::get('filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', null, ['class' => 'text-center'], [], true, Param::get('filter-description', false)->asString(false)))
            ->addHeader(new Header('category', 'Родительская категория', null, ['class' => 'text-center', 'style' => 'width: 300px;'], [], true, Param::get('filter-category_id', false)->asString(false)))
            ->addHeader(new Header('price', '<span class="glyphicon glyphicon-ruble" title="Цена"></span>', null, ['class' => 'text-center', 'style' => 'width: 100px;'], ['class' => 'text-center'], true, Param::get('filter-price', false)->asString(false)))
            ->addHeader(new Header('priority', '<span class="glyphicon glyphicon-sort-by-attributes" title="Приоритет"></span>', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('filter-priority', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('filter-active', false)->asString(false)))
        ;

        $categoriesAndItems = $retriever->getCategoriesAndItems(
            $parentCategoryId,
            $dataGrid->getFilterConditions(),
            $dataGrid->pagination->getLimit(),
            $dataGrid->pagination->getOffset()
        );

        $dataSet = new ArrayDataSet($categoriesAndItems);
        $dataGrid->addDataSet($dataSet);
        $dataGridView->dataGrid = $dataGrid;

//        $this->buildBreadcrumbs();
//        $this->fillBreadcrumbs($parentPK);
//        $viewBreadcrumbs = new ViewBreadcrumbs();
//        $this->breadcrumbs->setIgnores(['page', 'back_params']);
//        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
//        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

//        $this->frame->bindView('content', $view);

        $this->frame->bindView('content', $dataGridView);
        $this->frame->render();
    }

} 