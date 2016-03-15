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
        $this->viewCutString = new ViewCutString(30, true, ['class' => 'cut-string-display-to-modal', 'style' => 'cursor: pointer;'], ['class' => 'hidden']);
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
            <? if ($categoriesRealCount > 0): ?>
            <h3>Категории товаров</h3>
            <? while ($categoriesCount <= $categoriesRealCount): ?>
                <div class="row">
                <? $currentCategorySteps = $categoriesOffset + $this->categoriesPerRow > $categoriesRealCount ? $categoriesRealCount : $categoriesOffset + $this->categoriesPerRow ?>
                <? for ($i = $categoriesOffset; $i < $currentCategorySteps; $i++): ?>
                    <?
                    $oCategory = $this->aCategories[$i];
                    $this->viewCutString->setValue($oCategory->description);
                    ?>
                    <div class="col-lg-<?= ceil(12 / $this->categoriesPerRow) ?> col-md-<?= ceil(12 / $this->categoriesPerRow) ?> col-sm-<?= ceil(12 / ceil($this->categoriesPerRow / 2)) ?> col-xs-<?= ceil(12 / ceil($this->categoriesPerRow / 4)) ?>">
                        <div class="thumbnail">
                            <a href="?category_id=<?= $oCategory->id ?>">
                                <img src="<?= $oCategory->thumbnail ?>" style="max-height: 100px;" alt="Миниатюра"/>
                            </a>
                            <div class="caption text-center" style="overflow: hidden; height: 100px;">
                                <h4><a href="?category_id=<?= $oCategory->id ?>"><?= $oCategory->name ?></a></h4>
                                <div class="text-left">
                                    <? $this->viewCutString->render() ?>
                                </div>
                            </div>
                            <hr/>
                            <p class="text-center"><a href="?category_id=<?= $oCategory->id ?>" class="btn btn-default" role="button">Смотреть товары</a></p>
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
            <h3>Товары</h3>
            <? while ($itemsCount <= $itemsRealCount): ?>
            <div class="row">
                <? $currentItemSteps = $itemsOffset + $this->itemsPerRow > $itemsRealCount ? $itemsRealCount : $itemsOffset + $this->itemsPerRow ?>
                <? for ($i = $itemsOffset; $i < $currentItemSteps; $i++): ?>
                    <?
                    $oItem = $this->aItems[$i];
                    $this->viewCutString->setValue($oItem->description);
                    $this->viewMoney->setValue($oItem->price);
                    ?>
                    <div class="col-lg-<?= ceil(12 / $this->itemsPerRow) ?> col-md-<?= ceil(12 / $this->itemsPerRow) ?> col-sm-<?= ceil(12 / ceil($this->itemsPerRow / 2)) ?> col-xs-<?= ceil(12 / ceil($this->itemsPerRow / 4)) ?>">
                        <div class="thumbnail">
                            <a href="?action=show-item&item_id=<?= $oItem->id ?>">
                                <img src="<?= $oItem->thumbnail ?>" style="max-height: 100px;" alt="Миниатюра"/>
                            </a>
                            <div class="caption text-center" style="overflow: hidden; height: 100px;">
                                <h4><a href="?action=show-item&item_id=<?= $oItem->id ?>"><?= $oItem->name ?></a></h4>
                                <div class="text-left">
                                    <? $this->viewCutString->render() ?>
                                </div>
                            </div>
                            <hr/>
                            <h3 class="text-success text-center"><? $this->viewMoney->render() ?></h3>
                            <hr/>
                            <p class="text-center">
                                <a href="#" class="btn btn-success" role="button">В корзину</a>
                                <a href="?action=show-item&item_id=<?= $oItem->id ?>" class="btn btn-default" role="button">Подробнее</a>
                            </p>
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
                    <h3 class="text-center">Данная категория пуста. <a href="<?= $this->backUrl ?>" class="btn btn-default btn-sm">Вернуться назад</a></h3>
                </div>
            <? endif; ?>
        <?
    }

}