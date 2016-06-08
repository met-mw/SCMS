<?php
namespace App\Modules\Catalogue\Controllers;


use App\Classes\PublicAreaController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use App\Modules\Catalogue\Views\ViewCart;
use App\Modules\Catalogue\Views\ViewCatalogue;
use App\Modules\Catalogue\Views\ViewItem;
use SDataGrid\Classes\Column;
use SDataGrid\Classes\DataGrid;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerMain extends PublicAreaController {

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
                    SCMSNotificationLog::instance()->pushError("Товар не найден.");
                }
                break;
            case 'add-to-cart':
                $id = Param::get('id', true)->noEmpty('Товар не найден.')->asInteger(true, 'Неизвестный товар.');
                $count = Param::get('count', true)->noEmpty('Количество должно быть указано.')->asInteger(true, 'Не указано количество товара.');

                /** @var Item $oItem */
                $oItem = DataSource::factory(Item::cls(), $id);
                if (!$oItem) {
                    SCMSNotificationLog::instance()->pushError('Товар, который Вы пытаетесь добавить в корзину не существует.');
                }

                if (!$count) {
                    SCMSNotificationLog::instance()->pushError('Количество товара должно быть больше нуля.');
                }

                if (SCMSNotificationLog::instance()->hasProblems()) {
                    $this->Response->send();
                    return;
                }

                $this->cart->addItem($id, $count);
                SCMSNotificationLog::instance()->pushMessage("Товар \"{$oItem->name}\" в количестве {$count} (шт.) успешно добавлен в корзину. Теперь Вы можете перейти к оформлению заказа или продолжить покупки.");
                $this->Response->send('', ['totalCount' => $this->cart->getTotalCount()]);
                return;
                break;
            case 'show-cart':
                $view = new ViewCart();
                $cart = $this->cart;

                $DataGrid = new DataGrid();
                $aItemsInCart = $this->cart->getItems();
                $DataGrid->setCaption(empty($aItemsInCart) ? 'В Вашей корзине ещё нет ни одного товара' : 'Список товаров')
                    ->setAttributes(['class' => 'table table-condensed'])
                    ->addColumn(
                        (new Column())
                            ->setDisplayName('№')
                            ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setBodyAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->switchOnCounter()
                    )
                    ->addColumn(
                        (new Column())
                            ->setDisplayName('Наименование')
                            ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setValueName('name')
                    )
                    ->addColumn(
                        (new Column())
                            ->setDisplayName('Цена')
                            ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setBodyAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setFooterAttributes(['style' => 'text-align: center; vertical-align: middle;', 'class' => 'text-success'])
                            ->setCallback(
                                function (Item $oItem) {
                                    echo $oItem->price, '<span class="glyphicon glyphicon-ruble"></span>';
                                }
                            )
                            ->setFooterCallback(
                                function (array $aItems) {
                                    ?>Итого:<?
                                }
                            )
                    )
                    ->addColumn(
                        (new Column())
                            ->setDisplayName('Количество')
                            ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setBodyAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setFooterAttributes(['style' => 'text-align: center; vertical-align: middle;', 'class' => 'text-danger'])
                            ->setCallback(
                                function (Item $oItem) use ($cart) {
                                    ?><input type="number" min="1" class="form-control input-sm text-center catalogue-item-count" value="<?= $cart->getCountById($oItem->getPrimaryKey()) ?>" /><?
                                }
                            )
                            ->setFooterCallback(
                                function (array $aItems) use ($cart) {
                                    echo $cart->getTotalCount();
                                }
                            )
                    )
                    ->addColumn(
                        (new Column())
                            ->setDisplayName('Сумма')
                            ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setBodyAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setFooterAttributes(['style' => 'text-align: center; vertical-align: middle;', 'class' => 'text-danger'])
                            ->setCallback(
                                function (Item $oItem) use ($cart) {
                                    echo $cart->getCountById($oItem->getPrimaryKey()) * $oItem->price, '<span class="glyphicon glyphicon-ruble"></span>';
                                }
                            )
                            ->setFooterCallback(
                                function(array $aItems) use ($cart) {
                                    /** @var Item[] $aItems */
                                    $totalPrice = 0;
                                    foreach ($aItems as $oItem) {
                                        $totalPrice += $cart->getCountById($oItem->getPrimaryKey()) * $oItem->price;
                                    }

                                    echo $totalPrice, '<span class="glyphicon glyphicon-ruble"></span>';
                                }
                            )
                    )
                    ->addColumn(
                        (new Column())
                            ->setDisplayName('<span class="glyphicon glyphicon-option-horizontal"></span>')
                            ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setBodyAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                            ->setCallback(
                                function (Item $oItem) {
                                    echo '<a href="/catalogue?action=remove-from-cart?id=' . $oItem->getPrimaryKey() .'" class="btn btn-danger btn-xs catalogue-cart-remove" title="Удалить товар из конзины"><span class="glyphicon glyphicon-remove"></span></a>';
                                }
                            )
                    )
                    ->setDataSet($aItemsInCart);

                $view->DataGrid = $DataGrid;
                $view->goodCount = $cart->getTotalCount();
                $view->render();
                return;
                break;
            case 'set-cart-items';
                $items = Param::get('items')->asArray();
                foreach ($items as $itemId => $count) {
                    $this->cart->setItemCount($itemId, $count);
                }
                $this->Response->send('', ['totalCount' => $this->cart->getTotalCount()]);
                return;
                break;
            case 'order':
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
        $this->BreadcrumbsView->Breadcrumbs = array_merge($this->BreadcrumbsView->Breadcrumbs, array_reverse($categoryBreadcrumbs));
        if (isset($oItem)) {
            $this->BreadcrumbsView->Breadcrumbs[] = new Breadcrumb($oItem->name, '');
        }

        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($this->BreadcrumbsView->Breadcrumbs, 1);
        $this->Frame->bindView('breadcrumbs', $this->BreadcrumbsView);

        $view->render();
    }

} 