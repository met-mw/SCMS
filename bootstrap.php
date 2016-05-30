<?php
use App\Classes\SCMSNotificationLog;
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
        SCMSNotificationLog::instance()->logSystemMessage(SCMSNotificationLog::TYPE_ERROR, "{$e['type']} | {$e['message']} | {$e['file']} | {$e['line']}");
    }
}

SCMSNotificationLog::instance()->setProductionMode();
//NotificationLog::instance()->setDevelopMode();

DataSource::setup('mysql', include('App' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'mysql.php'));
DataSource::setCurrent('mysql');

// Пользовательская часть
Registry::set('front', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('front')->setFrame('front');
Registry::frame('front')->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css');
Registry::frame('front')->addCss('/public/assets/css/general.css');
Registry::frame('front')->addCss('/public/assets/css/main-menu.css');
Registry::frame('front')->addCss('/public/assets/css/carousel-actions.css');
Registry::frame('front')->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig']);
Registry::frame('front')->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8']);
Registry::frame('front')->addMeta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);

// Панель управления
Registry::set('back', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('back')->setFrame('back');
Registry::frame('back')->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css');
Registry::frame('back')->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig']);
Registry::frame('back')->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8']);
Registry::frame('front')->addMeta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);

// "Чистая" панель управления
Registry::set('back_clear', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('back_clear')->setFrame('back_clear');
Registry::frame('back_clear')->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css');
Registry::frame('back_clear')->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig']);
Registry::frame('back_clear')->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8']);
Registry::frame('front')->addMeta(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);

Registry::set('config', include('App/Config/project.php'), true);

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
        SCMSNotificationLog::instance()->logSystemMessage(SCMSNotificationLog::TYPE_ERROR, $e->getMessage(), $e->getCode());
        $response = new Response(SCMSNotificationLog::instance());
        SCMSNotificationLog::instance()->pushAny(SCMSNotificationLog::TYPE_ERROR, $e->getMessage(), $e->getCode());
        $response->send();
    }
} else {
    throw new Exception('Конфигурация роутинга отсутствует.');
}