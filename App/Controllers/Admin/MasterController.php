<?php
namespace App\Controllers\Admin;


use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewMenu;
use SFramework\Classes\Controller;
use SFramework\Classes\Registry;
use SFramework\Classes\Router;

abstract class MasterController extends Controller {

    /** @var Router */
    protected $router;

    public function __construct() {
        $config = Registry::get('config');
        $this->frame = Registry::frame('back');
        $this->router = Registry::router();

        $mainMenu = new ViewMenu();

        $mainMenu
            ->addItem('structures', 'Структура')
            ->addItem('modules', 'Модули')
            ->addItem('modules/pages', 'Страницы')
            ->addItem('catalogue', 'Каталог')
            ->addItem('mails', 'Рассылки')
            ->addItem('callme', 'Заказанные звонки')
            ->addItem('siteusers', 'Пользователи')
        ;
        $mainMenu->currentPath = reset(explode('?', $this->router->getRoute()));
        $mainMenu->projectName = $config['name'];

        $routes = [
            '/admin/' => 'Панель управления',
            '/admin/structures/' => 'Структура сайта',
            '/admin/structures/add/' => 'Добавление',
            '/admin/structures/edit/' => 'Редактирование',
        ];

//        $breadcrumbs = new ViewBreadcrumbs();
//        $breadcrumbs->breadcrumbs = $this->breadcrumbs(Registry::router()->getRoute(), $routes);

        $this->frame->bindView('menu', $mainMenu);
//        $this->frame->bindView('breadcrumbs', $breadcrumbs);
    }

    protected function getCurrentRoute() {
        $route = implode('/', Registry::router()->explodeRoute());
        return $route == '' ? '/' : "/{$route}/";
    }

    protected function breadcrumbs($route, array $routes) {
        $parsed = explode('?', $route);
        $parsed = array_diff(explode('/', reset($parsed)), ['']);
        $breadcrumbs = [];
        $current = '/';
        foreach ($parsed as $element) {
            $current .= $element . '/';
            $breadcrumbs[$current] = $routes[$current];
        }

        return $breadcrumbs;
    }

} 