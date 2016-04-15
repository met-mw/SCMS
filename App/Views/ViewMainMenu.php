<?php
namespace App\Views;


class ViewMainMenu extends ViewMenu {

    public function currentRender() {
        ?>
        <div class="navbar navbar-custom" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="true">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand hidden-xs hidden-sm" href="/<?= $this->pathRoot ?>">
                        <img src="/public/assets/images/denas.svg"/>
                    </a>
                    <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="/<?= $this->pathRoot ?>"><img src="/public/assets/images/denas.svg"/></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <? $this->itemsList->render() ?>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </div>
    <?
    }

}