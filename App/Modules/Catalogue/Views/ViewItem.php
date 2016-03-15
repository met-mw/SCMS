<?php
namespace App\Modules\Catalogue\Views;


use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\View;
use SFramework\Views\DataGrid\ViewMoney;

class ViewItem extends View {

    /** @var Item */
    public $oItem;
    /** @var string */
    public $backUrl;
    /** @var ViewMoney */
    public $viewMoney;

    public function __construct() {
        $this->viewMoney = new ViewMoney('<span class="glyphicon glyphicon-ruble"></span>');
    }

    public function currentRender() {
        $this->viewMoney->setValue($this->oItem->price);
        switch ($this->oItem->count) {
            case -1:
                $count = 'есть';
                break;
            case 0:
                $count = 'отсутствует';
                break;
            default:
                $count = $this->oItem->count;
        }
        ?>
            <div class="row">
                <div class="col-lg-2 text-center img-responsive">
                    <img class="img-thumbnail" style="max-width: 50%;" src="<?= $this->oItem->thumbnail ?>"/>
                </div>
                <div class="col-lg-10">
                    <p>Наименование: <?= $this->oItem->name ?></p>
                    <p>Цена: <span class="text-success"><? $this->viewMoney->render() ?></span></p>
                    <p>На складе: <span class="text-primary"><?= $count ?></span></p>
                    <? if ($this->oItem->count != 0): ?>
                    <form>
                        <fieldset>
                            <div class="form-group">
                                <div class="input-group">
                                    <label for="catalogue-add-to-basket-count"></label>
                                    <input class="input-group-addon" type="number" min="1"<? if ($this->oItem->count != -1): ?> max="<?= $this->oItem->count ?>"<? endif; ?> id="catalogue-add-to-basket-count" name="catalogue-add-to-basket-count" value="1" />
                                    <button class="btn btn-success input-group-addon">Добавить в корзину</button>
                                    <button class="btn btn-primary input-group-addon">Оформить заказ</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                    <? endif; ?>
                </div>
            </div>
            <hr/>
            <?= $this->oItem->description ?>
        <?
    }

}