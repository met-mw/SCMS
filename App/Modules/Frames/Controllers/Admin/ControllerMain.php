<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Frames\Classes\Retrievers\FramesList;
use App\Modules\Frames\Views\Admin\ViewList;
use App\Views\Admin\ViewResponse;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $retriever = new FramesList();

        $view = new ViewList();
        $view->response = new ViewResponse($this->alertClass, $this->alertHeader, $this->alertText);

        $view->menu->addItem('Добавить', '/admin/modules/frames/edit/');
        $view->table
            ->addColumn('frameName', 'Наименование')
            ->setCaption('Фреймы');
        $view->table->tableHead->allowActions = true;
        $view->table->tableBody->data = $retriever->getList();
        $view->table->tableBody->addAction('Редактировать', '/admin/modules/frames/edit/', 'glyphicon-pencil');

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

} 