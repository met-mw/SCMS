<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewMain extends View {

    public function currentRender() {
        ?>
        <div class="page-header">
            <h1>SCMS<small>&trade;</small> <small>(Simple Content Management System)</small>.</h1>
            <blockquote>
                <p class="text-primary">Простая система управления контентом - теперь Вы можете просто взять и начнать бизнес в интернете.</p>
                <footer>С уважением, <a href="http://sproject.ru" target="_blank" <cite title="SProject">SProject</cite></a></footer>
            </blockquote>
        </div>
        <br/>
        <br/>
        <h3>Базовые модули системы:</h3>
        <div class="panel panel-info">
            <!-- Default panel contents -->
            <div class="panel-heading">Общая информация</div>
            <div class="panel-body">
                <p>
                    Модуль - элемент расширения функционала сайта.
                    Добавляя в систему новые модули, можно наделить сайт новыми возможностями.
                    Каждая страница сайта может отображать неограниченное количество модулей, благодаря этому, владелец сайта может получить любой желаемый функционал.
                </p>
            </div>
        </div>
        <br/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <dl>
                        <dt>
                            <span class="label label-danger" title="Системный">S</span>&nbsp;&laquo;Структура сайта&raquo;
                        </dt>
                        <dd>
                            Формирование структуры сайта путём создания/удаления/изменения страниц. Выбор режима отображения страниц и настройка путей.
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-danger" title="Системный">S</span>&nbsp;&laquo;Модули&raquo;
                        </dt>
                        <dd>
                            Подключение/отключение/сканирование/просмотр доступных модулей системы. Расширение функционала сайта выполняется за счёт подключения дополнительных модулей любой функциональности.
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-danger" title="Системный">S</span>&nbsp;&laquo;Сотрудники&raquo;
                        </dt>
                        <dd>
                            Предоставление и регулирование сотрудникам компании прав доступа к панели управления сайтом.
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-danger" title="Системный">S</span>&nbsp;&laquo;Статичные страницы&raquo;
                        </dt>
                        <dd>
                            Создание и редактирование статичных страниц сайта. Страницы, созданные при помощи данного модуля могут быть использованы для отображения в произвольных разделах сайта.
                            <hr/>
                        </dd>
                    </dl>
                </div>
                <div class="col-lg-6">
                    <dl>
                        <dt>
                            <span class="label label-info" title="Дополнительный">O</span>&nbsp;&laquo;Ресурсы&raquo;
                        </dt>
                        <dd>
                            Загрузка и управление ресурсами. Модуль позволяет загружать в хранилище сайта различные файлы с целью дальнейшего их использования (в дизайне, для скачивания и т.д.).
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-info" title="Дополнительный">O</span>&nbsp;&laquo;Почтовые рассылки&raquo;
                        </dt>
                        <dd>
                            Управление индивидуальными почтовыми отправлениями и массовыми рассылками. Предоставление интерфейса для удобного просмотра отправленных/полученных писем, а также для формирования шаблонов писем для отправки.
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-info" title="Дополнительный">O</span>&nbsp;&laquo;Пользователи&raquo;
                        </dt>
                        <dd>
                            Регистрация и авторизация произвольных пользователей на сайте. Модуль предоставляет механизмы для работы с пользователями сайта.
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-info" title="Дополнительный">O</span>&nbsp;&laquo;Новости&raquo;
                        </dt>
                        <dd>
                            Формирование и отображение новостной ленты. Позволяет публиковать на сайте различные новости.
                            <hr/>
                        </dd>
                        <dt>
                            <span class="label label-info" title="Дополнительный">O</span>&nbsp;&laquo;Каталог&raquo;
                        </dt>
                        <dd>
                            Формирование списка товаров и отобращение его на сайте. Модуль предоставляет механизм для управления товарами.
                            <hr/>
                        </dd>
                    </dl>
                </div>
            </div>
        <?
    }

}