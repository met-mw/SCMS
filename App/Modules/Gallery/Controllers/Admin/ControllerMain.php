<?php
namespace App\Modules\Gallery\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Classes\Retrievers\GalleryRetriever;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewCutString;
use SFramework\Views\DataGrid\ViewLink;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $pageNumber = Param::get('gallery-page', false)->asInteger(false);
        $itemsPerPage = Param::get('gallery-items-per-page', false)->asInteger(false);

        $manifest = $this->ModuleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new GalleryRetriever();
        $dataGrid = new DataGrid('gallery', '', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new Item('Создать новую галлерею', '/admin/modules/gallery/edit/'))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/gallery/edit/', 'edit', '', [], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/gallery/delete/', 'delete', '', [], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('gallery-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Название', new ViewLink('/admin/modules/gallery/item/?gallery_id={label}', false, 'id'), ['class' => 'text-center', 'style' => 'width: 250px;'], [], true, Param::get('gallery-filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', new ViewCutString(20, true, ['class' => 'content-to-modal', 'style' => 'cursor: pointer;'], ['style' => 'display: none;']), ['class' => 'text-center'], ['class' => 'modal-display-field'], true, Param::get('gallery-filter-description', false)->asString(false)))
        ;

        $galleries = $retriever->getGalleries(
            $dataGrid->getFilterConditions(),
            $dataGrid->Pagination->getLimit(),
            $dataGrid->Pagination->getOffset()
        );

        $dataSet = new ArrayDataSet($galleries);
        $dataGrid->addDataSet($dataSet);
        $dataGridView->dataGrid = $dataGrid;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Галерея', '/gallery')
        ];

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $dataGridView);
        $this->Frame->render();
    }

}