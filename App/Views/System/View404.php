<?php
namespace App\Views\System;


use SFramework\Classes\View;

class View404 extends View {

    public function currentRender() {
        ?>
        <div class="jumbotron text-center">
            <h2>404 Страница не найдена</h2>
            <img class="thumbnail img-responsive center-block" src="/public/assets/images/system/404_<?= rand(1, 4) ?>.png" style="height: 200px;">
            <p>Извините, но такой страницы нет на нашем сайте. Возможно, Вы ввели неправильный адрес или страница была удалена. Попробуйте вернуться на <a class="btn btn-primary btn-lg" href="/" role="button">Главную страницу</a></p>
        </div>
        <?
    }

}