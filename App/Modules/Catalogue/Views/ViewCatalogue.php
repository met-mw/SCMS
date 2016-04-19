<?php
namespace App\Modules\Catalogue\Views;


use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\View;
use SFramework\Views\DataGrid\ViewCutString;
use SFramework\Views\DataGrid\ViewMoney;

class ViewCatalogue extends View {

    /** @var int */
    public $categoriesPerRow;
    /** @var int */
    public $itemsPerRow;
    /** @var Category[] */
    public $aCategories = [];
    /** @var Item[] */
    public $aItems = [];
    /** @var string */
    public $backUrl;

    /** @var ViewCutString */
    public $viewCutString;
    /** @var ViewMoney */
    public $viewMoney;

    public function __construct() {
        $this->viewCutString = new ViewCutString(30, true, ['class' => 'content-to-modal', 'style' => 'cursor: pointer;'], ['class' => 'hidden']);
        $this->viewMoney = new ViewMoney('<span class="glyphicon glyphicon-ruble"></span>');
    }

    public function currentRender() {
        $categoriesRealCount = sizeof($this->aCategories);
        $itemsRealCount = sizeof($this->aItems);

        $categoriesOffset = 0;
        $itemsOffset = 0;
        $categoriesCount = 1;
        $itemsCount = 1;
        ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form class="form-inline" method="get">
                        <fieldset>
                            <div class="row">
                                <div class="col-xs-8 col-sm-10">
                                    <input class="form-control" style="width: 100%;" type="text" name="catalogue-search" placeholder="Поиск по каталогу" />
                                </div>
                                <div class="col-xs-4 col-sm-2">
                                    <button class="btn btn-default" style="width: 100%;" type="submit">Искать</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <br/>
            <? if ($categoriesRealCount > 0): ?>
            <? while ($categoriesCount <= $categoriesRealCount): ?>
                <div class="row">
                <? $currentCategorySteps = $categoriesOffset + $this->categoriesPerRow > $categoriesRealCount ? $categoriesRealCount : $categoriesOffset + $this->categoriesPerRow ?>
                <? for ($i = $categoriesOffset; $i < $currentCategorySteps; $i++): ?>
                    <?
                    $oCategory = $this->aCategories[$i];
                    $this->viewCutString->setValue($oCategory->description);
                    ?>
                    <div class="col-lg-<?= ceil(12 / $this->categoriesPerRow) ?> col-md-<?= ceil(12 / $this->categoriesPerRow) ?> col-sm-<?= ceil(12 / ceil($this->categoriesPerRow / 2)) ?> col-xs-<?= ceil(12 / ceil($this->categoriesPerRow / 2)) ?>">
                        <div class="thumbnail">
                            <div class="caption text-center">
                                <h4><a href="?category_id=<?= $oCategory->id ?>"><?= $oCategory->name ?></a></h4>
                            </div>
                            <a href="?category_id=<?= $oCategory->id ?>">
                                <img src="<?= $oCategory->thumbnail ?>" style="max-height: 150px;" alt="<?= $oCategory->name ?>"/>
                            </a>
                            <div class="caption text-center" style="overflow: hidden; height: 100px;">
                                <div><? $this->viewCutString->render() ?></div>
                            </div>
                            <div class="caption" style="background-color: #f9f9f9; border-top: 1px solid #e5e5e5;">
                                <p class="text-center"><a href="?category_id=<?= $oCategory->id ?>" class="btn btn-default" role="button">Смотреть товары</a></p>
                            </div>
                        </div>
                    </div>
                    <? $categoriesCount++; ?>
                <? endfor; ?>
                </div>
                <? $categoriesOffset += $this->categoriesPerRow; ?>
            <? endwhile; ?>
                <? if ($itemsRealCount > 0): ?>
                <hr/>
                <? endif; ?>
            <? endif; ?>
            <? if ($itemsRealCount > 0): ?>
            <? while ($itemsCount <= $itemsRealCount): ?>
            <div class="row">
                <? $currentItemSteps = $itemsOffset + $this->itemsPerRow > $itemsRealCount ? $itemsRealCount : $itemsOffset + $this->itemsPerRow ?>
                <? for ($i = $itemsOffset; $i < $currentItemSteps; $i++): ?>
                    <?
                    $oItem = $this->aItems[$i];
                    $this->viewCutString->setValue($oItem->description);
                    $this->viewMoney->setValue($oItem->price);
                    ?>
                    <div class="col-lg-<?= ceil(12 / $this->itemsPerRow) ?> col-md-<?= ceil(12 / $this->itemsPerRow) ?> col-sm-<?= ceil(12 / ceil($this->itemsPerRow / 2)) ?> col-xs-<?= ceil(12 / ceil($this->itemsPerRow / 2)) ?>">
                        <div class="thumbnail">
                            <div class="caption text-center">
                                <h4><a href="?action=show-item&item_id=<?= $oItem->id ?>"><?= $oItem->name ?></a></h4>
                            </div>
                            <a href="?action=show-item&item_id=<?= $oItem->id ?>">
                                <img src="<?= $oItem->thumbnail ?>" style="max-height: 150px;" alt="<?= $oItem->name ?>"/>
                            </a>
                            <div class="caption text-center" style="overflow: hidden; height: 100px;">
                                <div><? $this->viewCutString->render() ?></div>
                            </div>
                            <div class="caption text-center" style="border-top: 1px solid #e5e5e5;">
                                <p class="text-success" style="font-size: 20px;"><? $this->viewMoney->render() ?></p>
                            </div>
                            <div class="caption" style="background-color: #f9f9f9; border-top: 1px solid #e5e5e5;">
                                <div class="text-center">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a href="?action=show-item&item_id=<?= $oItem->id ?>" class="btn btn-default" role="button" title="Просмотреть подробности">
                                                <span class="glyphicon glyphicon-eye-open" title="Просмотреть подробности"></span>
                                            </a>
                                        </span>
                                        <input type="number" min="1" value="1" class="form-control text-center" placeholder="Количество" />
                                        <span class="input-group-btn">
                                            <a href="/catalogue?action=add-to-cart&id=<?= $oItem->id ?>" class="btn btn-success catalogue-add-to-cart" role="button" title="Положить в корзину">
                                                <span class="glyphicon glyphicon-shopping-cart" title="Положить в корзину"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? $itemsCount++; ?>
                <? endfor; ?>
            </div>
            <? $itemsOffset += $this->itemsPerRow; ?>
            <? endwhile; ?>
            <? endif; ?>

            <? if ($itemsRealCount + $categoriesRealCount == 0): ?>
                <div class="block">
                    <h3 class="text-center">Данная категория товаров пуста.</h3>
                    <div class="block text-center">
                        <a href="<?= $this->backUrl ?>">Вернуться назад</a>
                    </div>
                    <br/>
                </div>
            <? endif; ?>
        <?
    }

}