<?php
namespace App\Modules\Gallery\Controllers\Admin\Item;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Classes\Retrievers\GalleryRetriever;
use App\Modules\Gallery\Models\Gallery;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewCondition;
use SFramework\Views\DataGrid\ViewCutString;
use SFramework\Views\DataGrid\ViewImageLink;
use SFramework\Views\DataGrid\ViewStub;
use SORM\DataSource;

class ControllerMain extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryId = Param::get('gallery_id')->asInteger(true, 'Недопустимое значение номера галлереи.');

        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $galleryId);
        if (!$oGallery) {
            NotificationLog::instance()->pushError("Запрошенная галлерея с номером \"{$galleryId}\" не существует.");
            $this->Frame->render();

            return;
        }

        $pageNumber = Param::get('gallery-item-page', false)->asInteger(false);
        $itemsPerPage = Param::get('gallery-item-items-per-page', false)->asInteger(false);

        $manifest = $this->ModuleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new GalleryRetriever();
        $dataGrid = new DataGrid('item', '', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new Item('Добавить элемент', "/admin/modules/gallery/item/edit/?gallery_id={$oGallery->id}"))
        ;

        $dataGrid
            ->addAction(new Action('id', "/admin/modules/gallery/item/edit/?gallery_id={$oGallery->id}", 'edit', '', [], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/gallery/item/delete/', 'delete', '', [], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('item-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Название', null, ['class' => 'text-center', 'style' => 'width: 250px;'], [], true, Param::get('item-filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', new ViewCutString(20, true, ['class' => 'content-to-modal', 'style' => 'cursor: pointer;'], ['style' => 'display: none;']), ['class' => 'text-center'], ['class' => 'modal-display-field'], true, Param::get('item-filter-description', false)->asString(false)))
            ->addHeader(new Header('path', 'Миниатюра', new ViewCondition(new ViewImageLink(true, ['class' => 'fancybox'], ['class' => 'img-rounded', 'style' => 'height: 20px;']), [['field' => 'path', 'value' => '/public/assets/images/system/no-image.svg', 'view' => new ViewStub('<span class="glyphicon glyphicon-picture"></span>')]]), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center']))
            ->addHeader(new Header('position', 'Позиция', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('item-filter-position', false)->asString(false)))
        ;

        $galleries = $retriever->getGalleryItems(
            $oGallery,
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
            new Breadcrumb('Галлереи', '/gallery'),
            new Breadcrumb("Элементы галлереи \"{$oGallery->name}\"", ''),
        ];

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $dataGridView);
        $this->Frame->render();
    }

}