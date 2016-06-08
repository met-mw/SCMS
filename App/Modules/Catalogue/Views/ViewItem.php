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
                <div class="col-md-3 col-sm-6 text-center">
                    <div class="text-center">
                        <h4 class="text-center">Поделитесь в соцсетях:</h4>
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
                    </div>

                    <a href="<?= $this->oItem->thumbnail ?>" class="fancybox">
                        <img class="img-responsive" src="<?= $this->oItem->thumbnail ?>"/>
                    </a>
                    <? if ($this->oItem->count != 0): ?>
                        <div class="input-group">
                            <input type="number" min="1" value="1" class="form-control text-center" placeholder="Количество" />
                            <span class="input-group-btn">
                                <a href="/catalogue?action=add-to-cart&id=<?= $this->oItem->id ?>" class="btn btn-success catalogue-add-to-cart" role="button" title="Положить в корзину">
                                    <span class="glyphicon glyphicon-shopping-cart" title="Положить в корзину"></span>&nbsp;В корзину
                                </a>
                            </span>
                        </div>
                        <br/>
                    <? endif; ?>
                </div>
                <div class="col-md-9 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-9"><h4>Информация о товаре</h4></div>
                                <div class="col-xs-3 text-right"><span class="glyphicon glyphicon-pushpin"></span></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <dl class="dl-horizontal" style="font-size: 1.3em;">
                                <dt>Наименование:</dt>
                                <dd><?= $this->oItem->name ?></dd>

                                <dt>Цена:</dt>
                                <dd><span class="text-success"><?= $this->oItem->price ?></span><span class="glyphicon glyphicon-ruble"></span></dd>

                                <dt>Наличие:</dt>
                                <dd><?= $count ?></dd>
                            </dl>
                        </div>
                        <div class="panel-footer">
                            <div class="text-right">
                                <span class="glyphicon glyphicon-asterisk text-warning" style=""></span>&nbsp;<em>Положите товар в корзину в нужном количестве и нажмите</em>&nbsp;&nbsp;
                                <a href="/catalogue/?action=show-cart" class="btn btn-primary" role="button" data-toggle="modal" data-target="#modal-catalogue-cart" title="Оформить заказ">
                                    <span class="glyphicon glyphicon-ok-circle" title="Оформить заказ"></span>&nbsp;Оформление заказа
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <?= $this->oItem->description ?>
        <?
    }

}