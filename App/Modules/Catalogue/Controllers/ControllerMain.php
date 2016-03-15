<?php
namespace App\Modules\Catalogue\Controllers;


use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use App\Modules\Catalogue\Views\ViewCatalogue;
use App\Modules\Catalogue\Views\ViewItem;
use App\Modules\Modules\Classes\MasterController;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerMain extends MasterController {

    public function actionIndex() {
        $action = Param::request('action', false)->asString(false);
        $categoryId = 0;

        switch ($action) {
            case 'show-item':
                $itemId = Param::get('item_id', false)->asInteger(false);
                /** @var Item $oItem */
                $oItem = DataSource::factory(Item::cls(), $itemId);

                $view = new ViewItem();

                if (!$oItem->isNew()) {
                    $categoryId = $oItem->category_id;
                    $view->oItem = $oItem;
                } else {
                    NotificationLog::instance()->pushError("Товар не найден.");
                }
                break;
            case 'add-to-basket':
                $view = new ViewItem();
                break;
            case 'show-basket':
                $view = new ViewItem();
                break;
            default:
                $categoryId = Param::get('category_id', false)->asInteger(false);
                if (!$categoryId) {
                    $categoryId = 0;
                }

                $oCategories = DataSource::factory(Category::cls());
                $oCategories->builder()
                    ->where('deleted=0')
                    ->whereAnd()
                    ->where('active=1')
                    ->whereAnd()
                    ->where("category_id={$categoryId}");
                $aCategories = $oCategories->findAll();

                $oItems = DataSource::factory(Item::cls());
                $oItems->builder()
                    ->where('deleted=0')
                    ->whereAnd()
                    ->where('active=1')
                    ->whereAnd()
                    ->where("category_id={$categoryId}");
                $aItems = $oItems->findAll();

                $view = new ViewCatalogue();
                $view->categoriesPerRow = 4;
                $view->itemsPerRow = 4;
                $view->aCategories = $aCategories;
                $view->aItems = $aItems;
        }

        // Подготовка хлебных крошек
        $breadcrumbsParentPK = $categoryId;
        $categoryBreadcrumbs = [];
        while($breadcrumbsParentPK) {
            /** @var Category $oParentCategory */
            $oParentCategory = DataSource::factory(Category::cls(), $breadcrumbsParentPK);
            $categoryBreadcrumbs[] = new Breadcrumb($oParentCategory->name, "?category_id={$oParentCategory->getPrimaryKey()}", true);
            $breadcrumbsParentPK = $oParentCategory->category_id;
        }
        $this->breadcrumbsView->breadcrumbs = array_merge($this->breadcrumbsView->breadcrumbs, array_reverse($categoryBreadcrumbs));
        if (isset($oItem)) {
            $this->breadcrumbsView->breadcrumbs[] = new Breadcrumb($oItem->name, '');
        }

        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($this->breadcrumbsView->breadcrumbs, 1);

        $this->frame->bindView('breadcrumbs', $this->breadcrumbsView);
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

} 