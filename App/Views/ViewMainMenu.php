<?php
namespace App\Views;


class ViewMainMenu extends ViewMenu {

    public function currentRender() {
        ?>
        <div class="navbar navbar-custom navbar-static-top" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="true">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand hidden-xs" href="/<?= $this->pathRoot ?>"><?= $this->projectName ?></a>
                    <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="/<?= $this->pathRoot ?>"><?= $this->projectName ?> - Главное меню</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <? $this->itemsList->render() ?>

                    <ul class="nav navbar-nav navbar-right hidden-xs hidden-sm">
                        <li><a href="#">Заказать звонок</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </div>
    <?
    }

}