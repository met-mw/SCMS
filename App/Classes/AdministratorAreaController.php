<?php
namespace App\Classes;


use App\Modules\Employees\Classes\Authorizator;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewInformationModal;
use App\Views\Admin\ViewMenu;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\Controller;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Pagination;
use SFramework\Classes\Registry;
use SFramework\Classes\Response;
use SFramework\Classes\Router;
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
abstract class AdministratorAreaController extends Controller
{

    /** @var Router */
    protected $Router;
    /** @var string */
    protected $moduleName;
    /** @var array */
    protected $config;
    /** @var Authorizator */
    protected $EmployeeAuthorizator;
    /** @var Pagination */
    protected $Pagination;
    /** @var Response */
    protected $Response;
    /** @var ModuleInstaller */
    protected $ModuleInstaller;

    public function __construct($moduleName = '')
    {
        $this->moduleName = $moduleName;

        $this->ModuleInstaller = new ModuleInstaller($this->moduleName);
        $this->Response = new Response(SCMSNotificationLog::instance());

        $this->config = Registry::get('config');
        $this->Frame = Registry::frame('back');
        $this->Router = Registry::router();
        $this->EmployeeAuthorizator = new Authorizator();

        $this->Frame->addCss('/public/assets/js/fancybox2/source/jquery.fancybox.css');
        $this->Frame->bindView('menu', $this->buildMenu());
        $this->Frame->bindView('modal-notification', new ViewNotificationsModal());
        $this->Frame->bindView('modal-confirmation', new ViewConfirmationModal());
        $this->Frame->bindView('modal-information', new ViewInformationModal());
    }

    /**
     * @return ViewMenu
     */
    private function buildMenu()
    {
        $mainMenu = new ViewMenu($this->config['name'], $this->EmployeeAuthorizator->getCurrentUser());
        $mainMenu->itemsList
            ->addItem('', 'Панель управления')
            ->addItem('configuration', 'Конфигурация системы')
            ->addItem('modules', 'Инструменты')
            ->addItem('service', 'Сервис')
        ;
        $modulesMenu = $mainMenu->itemsList->getItem('modules');
        $modulesMenu->itemsList
            ->addItem('structures', 'Структура сайта')
            ->addItem('pages', 'Статичные страницы')
            ->addItem('catalogue', 'Каталог товаров')
            ->addItem('employees', 'Управление сотрудниками')
            ->addItem('siteusers', 'Управление пользователями')
            ->addItem('gallery', 'Галерея изображений')
            ->addItem('news', 'Новостная лента')
            ->addItem('frames', 'Макеты сайта')
            ->addItem('modules', 'Управление модулями')
        ;

        $serviceMenu = $mainMenu->itemsList->getItem('service');
        $serviceMenu->itemsList
            ->addItem('moduleinstaller', 'Установщик модулей')
            ->addItem('about', 'О проекте')
            ->addItem('licence', 'Лицензионное соглашение')
        ;

        $currentPath = explode('?', $this->Router->getRoute());
        $mainMenu->currentPath = reset($currentPath);

        return $mainMenu;
    }

    public function authorizeIfNot()
    {
        if (!$this->EmployeeAuthorizator->authorized()) {
            header('Location: /admin/modules/employees/authorization');
            exit;
        }
    }

} 