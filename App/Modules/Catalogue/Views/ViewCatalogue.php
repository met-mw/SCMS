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
                <div class="col-sm-6 col-md-8" style="font-size: 1.4em; padding-bottom: 10px;">
                    <span class="label label-primary">Всего категорий:&nbsp;<?= $categoriesRealCount ?></span>
                    <span class="label label-primary">Всего товаров:&nbsp;<?= $itemsRealCount ?></span>
                </div>
                <div class="col-sm-6 col-md-4 text-right">
                    <form class="form-inline" method="get">
                        <fieldset>
                            <div class="input-group">
                                <input type="text" class="form-control" name="catalogue-search" placeholder="Найти в каталоге...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" title="Начать поиск"><span class="glyphicon glyphicon-search"></span></button>
                                </span>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <br/>
            <? if ($categoriesRealCount > 0): ?>
                <div class="row eq-height">
                <? for ($i = $categoriesOffset; $i < $categoriesRealCount; $i++): ?>
                    <?
                    $oCategory = $this->aCategories[$i];
                    $this->viewCutString->setValue($oCategory->description);
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="btn-group-vertical center-block" style="width: 100%;" role="group">
                            <a type="button" class="content-to-modal btn btn-default" role="button" title="Просмотреть описание во всплывающем окне"><span class="glyphicon glyphicon-blackboard"></span>&nbsp;Смотреть описание</a>
                            <div class="hidden" style="display: none;"><?= $oCategory->description ?></div>
                            <a href="?category_id=<?= $oCategory->id ?>" class="btn" style="border-left: 1px #ccc solid; border-right: 1px #ccc solid;"><h4><?= $oCategory->name ?></h4></a>
                            <a href="<?= $oCategory->thumbnail ?>" class="btn fancybox" style="border-left: 1px #ccc solid; border-right: 1px #ccc solid;">
                                <img src="<?= $oCategory->thumbnail ?>" style="max-height: 150px;" alt="<?= $oCategory->name ?>"/>
                            </a>
                            <a href="?category_id=<?= $oCategory->id ?>" class="btn btn-info" role="button" title="Просмотреть подробности">
                                <span class="glyphicon glyphicon-eye-open"></span>&nbsp;Смотреть товары
                            </a>
                        </div>
                    </div>
                <? endfor; ?>
                </div>
                <? if ($itemsRealCount > 0): ?>
                <hr/>
                <? endif; ?>
            <? endif; ?>
            <? if ($itemsRealCount > 0): ?>
            <? while ($itemsCount <= $itemsRealCount): ?>
            <div class="row eq-height">
                <? $currentItemSteps = $itemsOffset + $this->itemsPerRow > $itemsRealCount ? $itemsRealCount : $itemsOffset + $this->itemsPerRow ?>
                <? for ($i = $itemsOffset; $i < $currentItemSteps; $i++): ?>
                    <?
                    $oItem = $this->aItems[$i];
                    $this->viewCutString->setValue($oItem->description);
                    $this->viewMoney->setValue($oItem->price);
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                        <div class="btn-group-vertical center-block" style="width: 100%;" role="group">
                            <a type="button" class="content-to-modal btn btn-default" role="button" title="Просмотреть описание во всплывающем окне"><span class="glyphicon glyphicon-blackboard"></span>&nbsp;Смотреть описание</a>
                            <div class="hidden" style="display: none;"><?= $oItem->description ?></div>
                            <a href="?action=show-item&item_id=<?= $oItem->id ?>" class="btn" style="border-left: 1px #ccc solid; border-right: 1px #ccc solid;"><h4><?= $oItem->name ?></h4></a>
                            <div class="text-center" style="padding-bottom: 5px; padding-top: 5px; border-top: 0; border-left: 1px #ccc solid; border-right: 1px #ccc solid;">
                                <span class="text-success"><?= $oItem->price ?></span>&nbsp;<span class="glyphicon glyphicon-ruble"></span>
                            </div>
                            <div class="btn" style="border-bottom: 0; border-left: 1px #ccc solid; border-right: 1px #ccc solid;">
                                <div class="input-group">
                                    <input type="number" min="1" value="1" class="form-control text-center" placeholder="Количество" />
                                    <span class="input-group-btn">
                                        <a href="/catalogue?action=add-to-cart&id=<?= $oItem->id ?>" class="btn btn-success catalogue-add-to-cart" role="button" title="Положить в корзину">
                                            <span class="glyphicon glyphicon-shopping-cart" title="Положить в корзину"></span>&nbsp;В корзину
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <a href="<?= $oItem->thumbnail ?>" class="btn fancybox" style="border-bottom: 0; border-left: 1px #ccc solid; border-right: 1px #ccc solid;">
                                <img src="<?= $oItem->thumbnail ?>" style="max-height: 150px;" alt="<?= $oItem->name ?>"/>
                            </a>
                            <a href="?action=show-item&item_id=<?= $oItem->id ?>" class="btn btn-info" role="button" title="Просмотреть подробности">
                                <span class="glyphicon glyphicon-eye-open"></span>&nbsp;Смотреть детали
                            </a>
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