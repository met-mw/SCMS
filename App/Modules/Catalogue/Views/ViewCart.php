<?php
namespace App\Modules\Catalogue\Views;


use App\Modules\Catalogue\Classes\Cart;
use SFramework\Classes\View;
use SFramework\Views\DataGrid\ViewMoney;

class ViewCart extends View
{

    /** @var Cart */
    public $cart;

    public function currentRender()
    {
        $priceView = new ViewMoney('<span class="glyphicon glyphicon-ruble"></span>');
        $items = $this->cart->getItems();
        $gross = 0;
        foreach ($items as $item) {
            $gross += $item->price;
        }
        $fullCount = 0;
        $total = 0;
        $step = 1;

        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Моя корзина</h4>
        </div>
        <div class="modal-body">
                <div class="container-fluid">
                    <? if (empty($items)): ?>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <h4>В Вашей корзине ещё нет ни одного товара.</h4>
                            </div>
                        </div>
                    <? else: ?>
                        <div class="row">
                            <div class="col-xs-1 text-center">№</div>
                            <div class="col-xs-4 text-center"><h4>Название</h4></div>
                            <div class="col-xs-2 text-center"><h4>Цена</h4></div>
                            <div class="col-xs-2 text-center"><h4>Кол-во</h4></div>
                            <div class="col-xs-2 text-center"><h4>Сумма</h4></div>
                            <div class="col-xs-1 text-center"><h5><span class="glyphicon glyphicon-option-horizontal"></span></h5></div>
                        </div>
                        <div class="row alert-info" style="height: 10px;"></div>
                        <? foreach ($items as $item): ?>
                            <? $priceView->setValue($item->price); ?>
                            <? $currentCount = $this->cart->getCountById($item->getPrimaryKey()); ?>
                            <? $currentTotalPrice = $item->price * $currentCount; ?>
                            <div class="row cart-data-row" style="margin-top: 3px; margin-bottom: 3px;<? if ($step != 1): ?> border-top: 1px solid #e5e5e5;<? endif; ?>">
                                <input type="hidden" name="id" value="<?= $item->getPrimaryKey() ?>" />
                                <input type="hidden" name="count" value="<?= $currentCount ?>" />

                                <div class="col-xs-1 text-center"><?= $step ?></div>
                                <div class="col-xs-4"><?= $item->name ?></div>
                                <div class="col-xs-2 text-center"><? $priceView->render(); ?></div>
                                <div class="col-xs-2 text-center">
                                    <input type="number" min="1" class="form-control input-sm text-center catalogue-item-count" value="<?= $currentCount ?>" />
                                </div>
                                <? $priceView->setValue($item->price * $currentCount); ?>
                                <div class="col-xs-2 text-center"><? $priceView->render(); ?></div>
                                <div class="col-xs-1 text-center">
                                    <a href="/catalogue?action=remove-from-cart?id=<?= $item->getPrimaryKey() ?>" class="btn btn-danger btn-xs catalogue-cart-remove" title="Удалить товар из конзины"><span class="glyphicon glyphicon-remove"></span></a>
                                </div>
                            </div>
                            <? $fullCount += $currentCount; ?>
                            <? $total += $currentTotalPrice; ?>
                            <? $step++ ?>
                        <? endforeach; ?>
                        <div class="row" style="border-top: 1px solid #e54850;">
                            <div class="col-xs-5"><h4>Итого:</h4></div>
                            <div class="col-xs-2 col-xs-offset-2 text-center"><h4><?= $fullCount ?></h4></div>
                            <? $priceView->setValue($total); ?>
                            <div class="col-xs-3 text-center"><h4 class="text-success"><? $priceView->render(); ?></h4></div>
                        </div>
                    <? endif; ?>
                </div>
        </div>
        <div class="modal-footer">
            <? if (!empty($items)): ?>
                <a href="/catalogue?action=order" class="btn btn-success" title="Приступить к оформлению заказа."><span class="glyphicon glyphicon-ok-circle"></span>&nbsp;Оформить заказ</a>
            <? endif; ?>
            <button type="button" class="btn btn-default" data-dismiss="modal" title="Закрыть корзину"><span class="glyphicon glyphicon-saved"></span>&nbsp;Сохранить и закрыть</button>
        </div>
        <?
    }

}