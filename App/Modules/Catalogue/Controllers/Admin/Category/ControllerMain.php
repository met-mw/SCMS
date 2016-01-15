<?php
namespace App\Modules\Catalogue\Controllers\Admin\Category;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Models\Catalogue;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Structures\Classes\Retrievers\Catalogue;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\Decorations\ViewCallback;
use App\Views\Admin\Entities\Decorations\ViewLink;
use App\Views\Admin\Entities\Decorations\ViewParent;
use App\Views\Admin\Entities\ViewList;
use Exception;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $catalogueId = Param::get('catalogue_pk')->asInteger(true, 'Не выбран каталог!');
        $parentCategoryId = Param::get('parent_pk', false);
        if ($parentCategoryId->exists()) {
            $parentCategoryId = $parentCategoryId->asInteger(true, 'Недопустимое значение параметра!');
        } else {
            $parentCategoryId = 0;
        }

        $retriever = new Catalogue();

        $view = new ViewList();
        $addItemUrl = "/admin/modules/catalogue/category/edit/";
        $view->menu->addItem('Добавить', $addItemUrl, 'glyphicon-plus');

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGrid = new DataGrid('id', $manifest['meta']['alias']);
        $dataGrid->addDataSet(new ArrayDataSet($retriever->getCategoriesAndItems($parentCategoryId)));


//        $view->table->caption = $manifest['meta']['alias'];
//        $view->table->description = $manifest['meta']['description'];
//
//        $view->table
//            ->addAction('Редактирование', '/admin/modules/catalogue/category/edit/', 'glyphicon-pencil')
//            ->addAction('Удалить', '/admin/modules/catalogue/category/delete/', 'glyphicon-trash', false, ['entity-delete'])
//        ;
//
//        /** @var CatalogueRetriever $oCatalogue */
//        $oCatalogue = DataSource::factory(CatalogueRetriever::cls(), $catalogueId);
//        /** @var Category $oCategories */
//        $oCategories = $oCatalogue->field()->prepareRelation(Category::cls());
//        $oCategories->builder()
//            ->sqlCalcFoundRows()
//            ->whereAnd()
//            ->where("category_id={$parentCategoryId}")
//            ->order('priority');
//
//        if ($this->pager) {
//            $oCategories->builder()
//                ->limit($this->pager->getLimit())
//                ->offset($this->pager->getOffset());
//        }
//
//        /** @var Category[] $aCategories */
//        $aCategories = $oCategories->findAll();
//        if ($this->pager) {
//            $this->pager->prepare();
//        }
//        $view->table->tableBody->data = $aCategories;
//
//        foreach($oCategories->getFieldsDisplayNames() as $name => $displayName) {
//            $view->table->addColumn($name, $displayName);
//        }
//
//        $view->table->tableBody->addDecoration('active', new ViewActive('active'));
//
//        $linkUrl = '/admin/modules/catalogue/category/';
//        $view->table->tableBody->addDecoration('name', new ViewLink('name', $linkUrl, ['catalogue_pk' => 'catalogue_id', 'category_pk' => 'id']));
//
//        $view->table->tableBody->addDecoration('category_id',
//            new ViewCallback(
//                'category_id',
//                function($categoryId) {
//                    if ($categoryId == 0) {
//                        return '';
//                    }
//
//                    /** @var Category $oCategory */
//                    $oCategory = DataSource::factory(Category::cls(), $categoryId);
//                    return $oCategory->name;
//                }
//            )
//        );
//        $view->table->tableBody->addDecoration('catalogue_id',
//            new ViewCallback(
//                'catalogue_id',
//                function($catalogueId) {
//                    if ($catalogueId == 0) {
//                        return '';
//                    }
//
//                    /** @var CatalogueRetriever $oCatalogue */
//                    $oCatalogue = DataSource::factory(Category::cls(), $catalogueId);
//                    return $oCatalogue->name;
//                }
//            )
//        );

        if ($this->pager) {
            $this->fillPager($view);
        }


//        $this->buildBreadcrumbs();
//        $this->fillBreadcrumbs($parentPK);
//        $viewBreadcrumbs = new ViewBreadcrumbs();
//        $this->breadcrumbs->setIgnores(['page', 'back_params']);
//        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
//        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

} 