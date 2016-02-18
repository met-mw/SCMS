<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Classes\Retrievers\CatalogueRetriever;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\Param;
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

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $retriever = new CatalogueRetriever();
        $dataGrid = new DataGrid('id', $manifest['meta']['description']);
        $dataGrid
            ->addHeader(new Header('id', '№', [], true, Param::get('filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Наименование', [], true, Param::get('filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', [], true, Param::get('filter-description', false)->asString(false)))
            ->addHeader(new Header('category_id', 'Категория', [], true, Param::get('filter-category_id', false)->asString(false)))
            ->addHeader(new Header('price', 'Цена', [], true, Param::get('filter-price', false)->asString(false)))
            ->addHeader(new Header('priority', 'Приоритет', [], true, Param::get('filter-priority', false)->asString(false)))
            ->addHeader(new Header('active', 'Активность', [], true, Param::get('filter-active', false)->asString(false)))
        ;

        $conditions = '';
        foreach ($dataGrid->getHeaders() as $header) {
            $headerValue = $header->getFilterValue();
            if ($header->isFiltered() && !empty($headerValue)) {
                $conditions .= empty($conditions) ? ' where' : ' and';
                $conditions .= " {$header->getKey()} like '{$header->getFilterValue()}'";
            }
        }

        $limit = null;
        $offset = null;
        if ($this->pager) {
            $limit = $this->pager->getLimit();
            $offset = $this->pager->getOffset();
        }

        $categoriesAndItems = $retriever->getCategoriesAndItems($parentCategoryId, $conditions, $limit, $offset);
        $dataSet = new ArrayDataSet($categoriesAndItems);
        $dataGrid->addDataSet($dataSet);

        if ($this->pager) {
            $this->pager->prepare();
        }

//        if ($this->pager) {
//            $this->fillPager($view);
//        }


//        $this->buildBreadcrumbs();
//        $this->fillBreadcrumbs($parentPK);
//        $viewBreadcrumbs = new ViewBreadcrumbs();
//        $this->breadcrumbs->setIgnores(['page', 'back_params']);
//        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
//        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

//        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

} 