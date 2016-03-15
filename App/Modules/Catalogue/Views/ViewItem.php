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
                $count = '<span class="text-success">много</span>';
                break;
            case 0:
                $count = '<span class="text-danger">отсутствует</span>';
                break;
            default:
                $count = '<span class="text-warning">есть</span>';
        }
        ?>
            <div class="row">
                <div class="col-lg-3 text-center img-responsive">
                    <img class="img-thumbnail" style="max-width: 50%;" src="<?= $this->oItem->thumbnail ?>"/>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <p>Наименование: <?= $this->oItem->name ?></p>
                            <p>Цена: <span class="text-success"><? $this->viewMoney->render() ?></span></p>
                            <p>На складе: <span class="text-primary"><?= $count ?></span></p>
                        </div>
                    </div>
                    <? if ($this->oItem->count != 0): ?>
                    <div class="row">
                        <div class="col-lg-5">
                            <form>
                                <fieldset>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input class="form-control" type="number" min="1"<? if ($this->oItem->count != -1): ?> max="<?= $this->oItem->count ?>"<? endif; ?> id="catalogue-add-to-basket-count" name="catalogue-add-to-basket-count" value="1" />
                                            <div class="input-group-btn">
                                                <button class="btn btn-success">В корзину</button>
                                                <button class="btn btn-primary">Оформить заказ</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-7"></div>
                    </div>
                    <? endif; ?>
                </div>
            </div>
            <hr/>
            <?= $this->oItem->description ?>
        <?
    }

}