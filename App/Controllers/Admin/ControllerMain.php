<?php
namespace App\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Classes\ModuleInstaller;
use App\Models\NotificationLog;
use App\Views\Admin\ViewMain;
use SORM\DataSource;
use SORM\Tools\Builder\Select;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $this->needAuthenticate();

        $ModuleInstaller = new ModuleInstaller();
        $view = new ViewMain();

        /** @var NotificationLog $oNotificationLogs */
        $oNotificationLogs = DataSource::factory(NotificationLog::cls());
        $oNotificationLogs->builder()
            ->order('date', 'desc')
            ->limit(100);

        /** @var NotificationLog[] $aNotificationLogs */
        $aNotificationLogs = $oNotificationLogs->findAll();

        $Select = new Select();
        DataSource::getCurrent()->query($Select->table('notification_log')->field('count(*)', 'count')->build());
        $logsCount = DataSource::getCurrent()->fetchAssoc()[0]['count'];

        $modulesManifests = $ModuleInstaller->findManifests();

        $view->aNotificationLogs = $aNotificationLogs;
        $view->modulesManifests = $modulesManifests;
        $view->logsCount = $logsCount;

        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

} 