<?php
namespace App\Modules\Modules\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Models\Module;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\ViewList;
use App\Views\Admin\ViewResponse;
use SORM\DataSource;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewList();
        $view->response = new ViewResponse($this->alertClass, $this->alertHeader, $this->alertText);

        $view->menu->addItem('Установить', '/admin/modules/show/');

        $view->table->caption = 'Модули';
        $view->table->tableHead->allowActions = true;
        $view->table->tableBody->addAction('Просмотр', '/admin/modules/show/', 'glyphicon-sunglasses');

        $modules = DataSource::factory(Module::cls());
        $modules->builder()->sqlCalcFoundRows();
        $view->table->tableBody->data = $modules->findAll();
        $view->table
            ->addColumn('id', '№')
            ->addColumn('name', 'Наименование')
            ->addColumn('alias', 'Псевдоним')
            ->addColumn('description', 'Описание')
            ->addColumn('active', 'Активен')
        ;
        $this->Pagination->prepare();
        $this->fillPager($view);

        $view->table->tableBody->addDecoration('active', new ViewActive('active'));

        $this->Frame->bindView('content', $view);

        $this->Frame->render();
    }

} 