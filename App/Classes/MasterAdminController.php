<?php
namespace App\Classes;


use App\Modules\Employees\Classes\Authorizator;
use App\Views\Admin\MainList\ViewList;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewMenu;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\Breadcrumbs;
use SFramework\Classes\Controller;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Pagination;
use SFramework\Classes\Param;
use SFramework\Classes\Registry;
use SFramework\Classes\Response;
use SFramework\Classes\Router;
use SORM\DataSource;
use SORM\Tools\Builder;

/**
 * Class MasterAdminController
 * @package App\Classes
 *
 * TODO: Нужно придумать, как избавить от предварительно подгрузки компонентов, если сотрудник не авторизован.
 * Сейчас выполняются генерации хлебных крошек, меню, отрисовываются представления, но это не нужно, если сотрудник
 * не авторизован.
 * Подводны камни: Система должна работать стабильно и без модуля Employees, который предоставляет механизм авторизации.
 */
abstract class MasterAdminController extends Controller {

    /** @var Router */
    protected $router;
    /** @var string */
    protected $moduleName;
    /** @var array */
    protected $config;
    /** @var Authorizator */
    protected $employeeAuthorizator;
    /** @var Pagination */
    protected $pager;
    /** @var Response */
    protected $response;
    /** @var ModuleInstaller */
    protected $moduleInstaller;

    public function __construct($moduleName = '') {
        $this->moduleName = $moduleName;

        $this->moduleInstaller = new ModuleInstaller($this->moduleName);
        $this->response = new Response(NotificationLog::instance());

        $this->config = Registry::get('config');
        $this->frame = Registry::frame('back');
        $this->router = Registry::router();
        $this->employeeAuthorizator = new Authorizator();

        $this->frame->addCss('/public/assets/js/fancybox2/source/jquery.fancybox.css');
        $this->frame->bindView('menu', $this->buildMenu());
        $this->frame->bindView('modal-notification', new ViewNotificationsModal());
        $this->frame->bindView('modal-confirmation', new ViewConfirmationModal());
    }

    /**
     * @return ViewMenu
     */
    private function buildMenu() {
        $mainMenu = new ViewMenu($this->config['name'], $this->employeeAuthorizator->getCurrentUser());
        $mainMenu->itemsList
            ->addItem('', 'Панель управления')
            ->addItem('configuration', 'Конфигурация')
            ->addItem('modules', 'Модули')
        ;
        $modulesMenu = $mainMenu->itemsList->getItem('modules');
        $modulesMenu->itemsList
            ->addItem('structures', 'Структура сайта')
            ->addItem('pages', 'Статичные страницы')
            ->addItem('catalogue', 'Каталог')
            ->addItem('employees', 'Сотрудники')
            ->addItem('frames', 'Фреймы')
            ->addItem('modules', 'Модули')
        ;

        $currentPath = explode('?', $this->router->getRoute());
        $mainMenu->currentPath = reset($currentPath);

        return $mainMenu;
    }

    public function authorizeIfNot() {
        if (!$this->employeeAuthorizator->authorized()) {
            header('Location: /admin/modules/employees/authorization');
            exit;
        }
    }

} 