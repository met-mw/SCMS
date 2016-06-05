<?php
namespace App\Controllers\Admin\Configuration;


use App\Classes\AdministratorAreaController;
use App\Classes\Configurator;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewConfiguration;
use SDataGrid\Classes\Column;
use SDataGrid\Classes\DataGrid;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Registry;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->needAuthenticate();
        $Configurator = new Configurator(Registry::get('config'));

        $view = new ViewConfiguration();
        $view->DataGrid = new DataGrid();
        $view->DataGrid->setCaption('SCMS параметры системы')
            ->setAttributes(['class' => 'table table-hover table-striped'])
            ->addColumn(
                (new Column())->setDisplayName('#')
                    ->setHeaderAttributes(['style' => 'vertical-align: middle;'])
                    ->switchOnCounter()
            )
            ->addColumn(
                (new Column())
                    ->setDisplayName('Наименование')
                    ->setValueName('name')
                    ->setHeaderAttributes(['style' => 'vertical-align: middle;'])
            )
            ->addColumn(
                (new Column())
                    ->setDisplayName('Значение')
                    ->setValueName('data')
                    ->setHeaderAttributes(['style' => 'vertical-align: middle;'])
                    ->setCallback(function($data) {
                        if (is_array($data['data'])) {
                            echo '<ul>';
                            foreach ($data['data'] as $value) {
                                echo "<li>{$value}</li>";
                            }
                            echo '</ul>';
                        } else {
                            echo $data['data'];
                        }
                    })
            )
            ->addColumn(
                (new Column())
                    ->setDisplayName('Новое значение')
                    ->setHeaderAttributes(['style' => 'vertical-align: middle;'])
                    ->setCallback(function($data) {
                        if (is_array($data['data'])) {
                            echo '<ul>';
                            foreach ($data['data'] as $name => $value) {
                                echo "<li><input name='{$name}' type='text' style='width: 100%;' /></li>";
                            }
                            echo '</ul>';
                        } else {
                            echo "<input name='{$data['name']}' type='text' style='width: 100%;' />";
                        }
                    })
            )
            ->setDataSet($Configurator->getProjectConfigAsRows());

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Настройки', '')
        ];

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

}