<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Frames\Classes\Retrievers\FramesRetriever;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\Param;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageNumber = Param::get('frames-page', false)->asInteger(false);
        $itemsPerPage = Param::get('frames-items-per-page', false)->asInteger(false);

        $dataGridView = new ViewDataGrid();
        $retriever = new FramesRetriever();

        $manifest = $this->ModuleInstaller->getManifest($this->ModuleDirectory);
        $dataGrid = new DataGrid('frames', '/admin/modules/frames/', 'name', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);
        $dataGrid->getMenu()
            ->addElement(new Item('Создать новый фрейм', '/admin/modules/frames/edit/'))
        ;

        $dataGrid
            ->addAction(new Action('name', '/admin/modules/frames/edit/', 'edit', '', [], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('name', '/admin/modules/frames/delete/', 'delete', '', [], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('name', 'Название', null, ['class' => 'text-center'], ['class' => 'text-left'], true, Param::get('frames-filter-id', false)->asString(false)));

        $arrayDataSet = new ArrayDataSet($retriever->getFrames());
        $dataGrid->addDataSet($arrayDataSet);
        $dataGridView->dataGrid = $dataGrid;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Фреймы', '/modules/frames')
        ];

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $dataGridView);
        $this->Frame->render();
    }

} 