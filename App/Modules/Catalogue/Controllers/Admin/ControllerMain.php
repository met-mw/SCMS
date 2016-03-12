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
use SFramework\Classes\Menu\Item;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewEmpty;
use SFramework\Views\DataGrid\ViewMoney;
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
        $pageNumber = Param::get('catalogue-page', false)->asInteger(false);
        $itemsPerPage = Param::get('catalogue-items-per-page', false)->asInteger(false);

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new CatalogueRetriever();
        $dataGrid = new DataGrid('catalogue', '/admin/modules/catalogue/', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new DataGrid\Menu\Item('Добавить категорию', '#'))
            ->addElement(new DataGrid\Menu\Item('Добавить позицию', '#'))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/catalogue/edit/', 'edit', '', ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/catalogue/delete/', 'delete', '', ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Наименование', null, ['class' => 'text-center', 'style' => 'width: 300px'], [], true, Param::get('catalogue-filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', null, ['class' => 'text-center'], [], true, Param::get('catalogue-filter-description', false)->asString(false)))
            ->addHeader(new Header('category', 'Родительская категория', null, ['class' => 'text-center', 'style' => 'width: 300px;'], [], true, Param::get('catalogue-filter-category_id', false)->asString(false)))
            ->addHeader(new Header('price', '<span class="glyphicon glyphicon-ruble" title="Цена"></span>', new ViewMoney('<span class="glyphicon glyphicon-ruble"></span>'), ['class' => 'text-center', 'style' => 'width: 100px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-price', false)->asString(false)))
            ->addHeader(new Header('priority', '<span class="glyphicon glyphicon-sort-by-attributes" title="Приоритет"></span>', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-priority', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-active', false)->asString(false)))
        ;

        $categoriesAndItems = $retriever->getCategoriesAndItems(
            $parentCategoryId,
            $dataGrid->getFilterConditions('childs'),
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