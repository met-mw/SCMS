<?php
namespace App\Modules\Siteusers\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Siteusers\Classes\Retrievers\SiteusersRetriever;
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
use SFramework\Views\DataGrid\ViewDateTime;
use SFramework\Views\DataGrid\ViewEmail;

class ControllerMain extends MasterAdminController
{

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->authorizeIfNot();

        $pageNumber = Param::get('employee-page', false)->asInteger(false);
        $itemsPerPage = Param::get('employee-items-per-page', false)->asInteger(false);

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $siteusersRetriever = new SiteusersRetriever();
        $dataGridView = new ViewDataGrid();
        $dataGrid = new DataGrid('siteuser', '', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new Item('Добавить нового пользователя', '/admin/modules/siteusers/edit/'))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/siteusers/edit/', 'edit', '', [], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/siteusers/delete/', 'delete', '', [], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('siteuser-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Имя', null, ['class' => 'text-center', 'style' => 'width: 250px;'], [], true, Param::get('siteuser-filter-name', false)->asString(false)))
            ->addHeader(new Header('surname', 'Фамилия', null, ['class' => 'text-center', 'style' => 'width: 250px;'], [], true, Param::get('siteuser-filter-surname', false)->asString(false)))
            ->addHeader(new Header('patronymic', 'Отчество', null, ['class' => 'text-center', 'style' => 'width: 250px;'], [], true, Param::get('siteuser-filter-patronymic', false)->asString(false)))
            ->addHeader(new Header('email', 'Email', new ViewEmail(), ['class' => 'text-center'], [], true, Param::get('siteuser-filter-email', false)->asString(false)))
            ->addHeader(new Header('phone', 'Телефон', null, ['class' => 'text-center'], [], true, Param::get('siteuser-filter-phone', false)->asString(false)))
            ->addHeader(new Header('mail_address', 'Адрес', new ViewCutString(20, true, ['class' => 'content-to-modal', 'style' => 'cursor: pointer;'], ['style' => 'display: none;']), ['class' => 'text-center'], ['class' => 'modal-display-field'], true, Param::get('siteuser-filter-mail_address', false)->asString(false)))
            ->addHeader(new Header('postcode', 'Индекс', null, ['class' => 'text-center'], [], true, Param::get('siteuser-filter-postcode', false)->asString(false)))
            ->addHeader(new Header('created', 'Создан', new ViewDateTime('d.m.Y h:i:s'), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('siteuser-filter-created', false)->asString(false)))
            ->addHeader(new Header('updated', 'Изменён',  new ViewDateTime('d.m.Y h:i:s'), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('siteuser-filter-updated', false)->asString(false)))
        ;

        $structures = $siteusersRetriever->getSiteusers(
            $dataGrid->getFilterConditions(),
            $dataGrid->pagination->getLimit(),
            $dataGrid->pagination->getOffset()
        );

        $dataSet = new ArrayDataSet($structures);
        $dataGrid->addDataSet($dataSet);
        $dataGridView->dataGrid = $dataGrid;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Пользователи', '/siteusers')
        ];

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $dataGridView);
        $this->frame->render();
    }

}