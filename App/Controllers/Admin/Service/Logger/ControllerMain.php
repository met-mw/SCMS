<?php


namespace App\Controllers\Admin\Service\Logger;


use App\Classes\AdministratorAreaController;
use App\Models\NotificationLog;
use App\Views\Admin\ViewConfiguration;
use SDataGrid\Classes\Column;
use SDataGrid\Classes\DataGrid;
use SORM\DataSource;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->needAuthenticate();

        $view = new ViewConfiguration();

        /** @var NotificationLog $oNotificationLogs */
        $oNotificationLogs = DataSource::factory(NotificationLog::cls());
        /** @var NotificationLog[] $aNotificationLogs */
        $aNotificationLogs = $oNotificationLogs->findAll();

        $DataGrid = new DataGrid();
        $DataGrid->setCaption('Список параметров')
            ->setAttributes(['class' => 'table table-bordered table-hover table-striped'])
            ->addColumn(
                (new Column())
                    ->setDisplayName('#')
                    ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                    ->switchOnCounter()
            )
            ->addColumn(
                (new Column())
                    ->setDisplayName('Тип')
                    ->setValueName('type')
                    ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                    ->setCallback(
                        function (NotificationLog $data) {
                            echo $data->type;
                        }
                    )
            )
            ->addColumn(
                (new Column())
                    ->setDisplayName('Сообщение')
                    ->setValueName('message')
                    ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
                    ->setBodyAttributes(['class' => 'text-justify'])
            )
            ->addColumn(
                (new Column())
                    ->setDisplayName('IP-адрес')
                    ->setValueName('ip')
                    ->setHeaderAttributes(['style' => 'text-align: center; vertical-align: middle;'])
            )
            ->setDataSet($aNotificationLogs);
        $view->DataGrid = $DataGrid;

        $this->getFrame()
            ->bindView('content', $view)
            ->render();
    }

}