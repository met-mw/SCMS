<?php
use SFramework\Classes\Frame;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Registry;
use SFramework\Classes\Router;
use SORM\DataSource;


require_once('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

NotificationLog::instance()->setProductionMode();
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

// Панель управления
Registry::set('back', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('back')->setFrame('back');
Registry::frame('back')->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css');
Registry::frame('back')->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig']);
Registry::frame('back')->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8']);

// "Чистая" панель управления
Registry::set('back_clear', new Frame(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR), true);
Registry::frame('back_clear')->setFrame('back_clear');
Registry::frame('back_clear')->addCss('/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css');
Registry::frame('back_clear')->addJs('/public/assets/js/bower_components/requirejs/require.js', ['data-main' => '/public/assets/js/appconfig']);
Registry::frame('back_clear')->addMeta(['http-equiv' => 'content-type', 'content' => 'text/html; charset=utf-8']);

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
    $router->route();
} else {
    throw new Exception('Конфигурация роутинга отсутствует.');
}