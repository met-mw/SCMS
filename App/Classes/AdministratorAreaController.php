<?php
namespace App\Classes;


use App\Classes\Authentication\Authentication;
use App\Classes\Authentication\HttpAuthentication;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewInformationModal;
use App\Views\Admin\ViewMenu;
use App\Views\Admin\ViewNotificationsModal;
use SFileSystem\Classes\Directory;
use SFramework\Classes\Controller;
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
    /** @var Directory */
    protected $ModuleDirectory;
    /** @var array */
    protected $config;
    /** @var Authentication */
    protected $EmployeeAuthentication;
    /** @var HttpAuthentication */
    protected $HTTPEmployeeAuthentication;
    /** @var Pagination */
    protected $Pagination;
    /** @var Response */
    protected $Response;
    /** @var ModuleInstaller */
    protected $ModuleInstaller;

    public function __construct($ModuleDirectory = null)
    {
        $this->ModuleDirectory = $ModuleDirectory;

        $this->ModuleInstaller = new ModuleInstaller();
        $this->Response = new Response(SCMSNotificationLog::instance());

        $this->config = Registry::get('config');
        $this->Frame = Registry::frame('back');
        $this->Router = Registry::router();
        $this->EmployeeAuthentication = new Authentication();
        $this->HTTPEmployeeAuthentication = new HttpAuthentication();

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
        $mainMenu = new ViewMenu($this->config['name'], $this->EmployeeAuthentication->getCurrentUser());
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
            ->addItem('logger', 'Системные оповещения')
            ->addItem('about', 'О проекте')
        ;

        $currentPath = explode('?', $this->Router->getRoute());
        $mainMenu->currentPath = reset($currentPath);

        return $mainMenu;
    }

    public function authorizeIfNot()
    {
        if (!$this->EmployeeAuthentication->authenticated()) {
            header('Location: /admin/modules/employees/authorization');
            exit;
        }
    }

    public function httpAuthorizeIfNot()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="SCMS Authentication System"');
            header('HTTP/1.0 401 Unauthorized');

            echo $this->Response->arrayToJSON(['authentication' => 'error', 'message' => 'Вы должны ввести корректный логин и пароль для получения доступа к ресурсу.']);
            exit;
        }

        $this->HTTPEmployeeAuthentication->signIn($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        if (!$this->HTTPEmployeeAuthentication->authenticated()) {
            header('WWW-Authenticate: Basic realm="SCMS Authentication System"');
            header('HTTP/1.0 401 Unauthorized');

            echo $this->Response->arrayToJSON(['authentication' => 'error', 'message' => 'Вы должны ввести корректный логин и пароль для получения доступа к ресурсу.']);
            exit;
        }
    }

} 