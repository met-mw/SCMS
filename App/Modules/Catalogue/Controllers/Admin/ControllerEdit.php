<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use App\Modules\Catalogue\Views\Admin\ViewCategoryEdit;
use App\Modules\Catalogue\Views\Admin\ViewItemEdit;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends AdministratorAreaController {

    public function actionIndex() {
        $this->needAuthenticate();

        $isCategory = Param::get('is_category')->asInteger(true, "Недопустимое значение параметра.");
        $id = Param::get('id', false)->asInteger(false);
        $parentId = Param::get('parent_pk', false)->asInteger(false);

        /** @var Category $oCategories */
        $oCategories = DataSource::factory(Category::cls());
        $oCategories->builder()->where('deleted=0');
        /** @var Category[] $aCategories */
        $aCategories = $oCategories->findAll();

        if ($isCategory == 1) {
            $viewCatalogueEdit = new ViewCategoryEdit();
            /** @var Category $oCategory */
            $oCategory = DataSource::factory(Category::cls(), $id);
            $viewCatalogueEdit->oCategory = $oCategory;
            $viewCatalogueEdit->aCategories = $aCategories;
            $viewCatalogueEdit->parentId = $oCategory->isNew() ? $parentId : $oCategory->category_id;
        } else {
            $viewCatalogueEdit = new ViewItemEdit();
            /** @var Item $oItem */
            $oItem = DataSource::factory(Item::cls(), $id);
            $viewCatalogueEdit->oItem = $oItem;
            $viewCatalogueEdit->aCategories = $aCategories;
            $viewCatalogueEdit->parentId = $oItem->isNew() ? $parentId : $oItem->category_id;
        }

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Каталог', '/modules/catalogue')
        ];

        if ($parentId) {
            $breadcrumbsParentPK = $parentId;
        } elseif ($isCategory == 1 && isset($oCategory)) {
            $breadcrumbsParentPK = $oCategory->category_id;
        } elseif (!$isCategory && isset($oItem)) {
            $breadcrumbsParentPK = $oItem->category_id;
        } else {
            $breadcrumbsParentPK = 0;
        }
        $catalogueEditBreadcrumbs = [];
        while($breadcrumbsParentPK) {
            /** @var Category $oParentCategory */
            $oParentCategory = DataSource::factory(Category::cls(), $breadcrumbsParentPK);
            $catalogueEditBreadcrumbs[] = new Breadcrumb($oParentCategory->name, "?parent_pk={$oParentCategory->getPrimaryKey()}", true);
            $breadcrumbsParentPK = $oParentCategory->category_id;
        }
        $viewBreadcrumbs->Breadcrumbs = array_merge($viewBreadcrumbs->Breadcrumbs, array_reverse($catalogueEditBreadcrumbs));
        if ($id && isset($oCategory)) {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb("Редактирование \"$oCategory->name\"", '');
        } elseif ($id && isset($oItem)) {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb("Редактирование \"$oItem->name\"", '');
        } else {
            $lastBreadcrumb = 'Добавление новой ' . ($isCategory == 1 ? 'категории' : 'позиции');
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb($lastBreadcrumb, '');
        }

        $viewCatalogueEdit->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->Breadcrumbs, 1);

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $viewCatalogueEdit);
        $this->Frame->render();
    }

} 