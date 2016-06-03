<?php
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Frame;
use SFramework\Classes\Registry;
use SFramework\Classes\Router;
use SORM\DataSource;
use SFramework\Classes\Response;


require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

// Регистрируем обработчик ошибок
register_shutdown_function('dbg_last_error');
function dbg_last_error()
{
    $e = error_get_last();
    if (($e['type'] & E_COMPILE_ERROR) || ($e['type'] & E_ERROR) || ($e['type'] & E_CORE_ERROR) || ($e['type'] & E_RECOVERABLE_ERROR)) {
        SCMSNotificationLog::instance()->DBLogger->critical("{$e['type']} | {$e['message']} | {$e['file']} | {$e['line']}");
    }
}

SCMSNotificationLog::instance()->setProductionMode();
//NotificationLog::instance()->setDevelopMode();

DataSource::setup('mysql', include('App' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'mysql.php'));
DataSource::setCurrent('mysql');

Registry::set('config', include('App/Config/project.php'), true);
Registry::set('credits', include('credits.php'));

// Пользовательская часть
Registry::set('front', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('front')->setFrame('front')
    ->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css')
    ->addCss('/public/assets/css/general.css')
    ->addCss('/public/assets/css/main-menu.css')
    ->addCss('/public/assets/css/carousel-actions.css')
    ->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig'])
    ->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8'])
    ->addMeta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'])
    ->setFavicon(Registry::get('config')['favicon']);

// Панель управления
Registry::set('back', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('back')->setFrame('back')
    ->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css')
    ->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig'])
    ->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8'])
    ->addMeta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'])
    ->setFavicon(Registry::get('config')['favicon']);

// "Чистая" панель управления
Registry::set('back_clear', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('back_clear')->setFrame('back_clear')
    ->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css')
    ->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig'])
    ->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8'])
    ->addMeta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'])
    ->setFavicon(Registry::get('config')['favicon']);

// Роутинг
$configFileName = 'App' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'route.php';
if (file_exists($configFileName)) {
    Registry::set('router', new Router(), true);
    $router = Registry::router();
    $router->setConfig(include($configFileName));
    $router->setRoute($_SERVER['REQUEST_URI']);
    try {
        $router->route();
    } catch (Exception $e) {
        SCMSNotificationLog::instance()->DBLogger->error($e->getMessage());
        $response = new Response(SCMSNotificationLog::instance());
        SCMSNotificationLog::instance()->pushAny(SCMSNotificationLog::TYPE_MESSAGE, $e->getMessage(), $e->getCode());
        $response->send();
    }
} else {
    throw new Exception('Конфигурация роутинга отсутствует.');
}