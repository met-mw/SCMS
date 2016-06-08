<?php
namespace App\Modules\Catalogue\Views;


use SDataGrid\Classes\DataGrid;
use SFramework\Classes\View;

class ViewCart extends View
{

    public $goodCount;
    /** @var DataGrid */
    public $DataGrid;

    public function currentRender()
    {
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Моя корзина</h4>
        </div>
        <div class="modal-body">
                <div class="container-fluid table-responsive">
                    <? $this->DataGrid->render() ?>
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