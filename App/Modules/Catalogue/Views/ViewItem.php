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
                <div class="col-lg-3 text-center" style="border-right: 1px solid #e5e5e5;">
                    <img class="img-responsive" src="<?= $this->oItem->thumbnail ?>"/>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-group">
                                <li class="list-group-item">Наименование: <?= $this->oItem->name ?></li>
                                <li class="list-group-item list-group-item-success">Цена: <span class="text-success"><? $this->viewMoney->render() ?></span></li>
                                <li class="list-group-item">На складе: <span class="text-primary"><?= $count ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <? if ($this->oItem->count != 0): ?>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <a href="/catalogue/?action=show-cart" class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal-catalogue-cart" title="Оформить заказ">
                                        <span class="glyphicon glyphicon-ok-circle" title="Оформить заказ"></span>
                                        <span class="hidden-xs">&nbsp;Оформить заказ</span>
                                    </a>
                                </span>
                                <input type="number" min="1" value="1" class="form-control text-center" placeholder="Количество" />
                                <span class="input-group-btn">
                                    <a href="/catalogue?action=add-to-cart&id=<?= $this->oItem->id ?>" class="btn btn-success catalogue-add-to-cart" role="button" title="Положить в корзину">
                                        <span class="glyphicon glyphicon-shopping-cart" title="Положить в корзину"></span>
                                        <span class="hidden-xs">&nbsp;Положить в корзину</span>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-7"></div>
                    </div>
                    <? endif; ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>Поделитесь в соцсетях:</h4>
                            <p>
                                <script type="text/javascript">(function() {
                                        if (window.pluso)if (typeof window.pluso.start == "function") return;
                                        if (window.ifpluso==undefined) { window.ifpluso = 1;
                                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                            s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                            s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                                            var h=d[g]('body')[0];
                                            h.appendChild(s);
                                        }})();</script>
                                <div class="pluso" data-background="transparent" data-options="big,round,line,horizontal,nocounter,theme=04" data-services="vkontakte,odnoklassniki,facebook,twitter"></div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <?= $this->oItem->description ?>
        <?
    }

}