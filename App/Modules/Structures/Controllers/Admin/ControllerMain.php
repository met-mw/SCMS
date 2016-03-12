<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Structures\Classes\Retrievers\StructureRetriever;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewEmpty;
use SFramework\Views\DataGrid\ViewLink;
use SFramework\Views\DataGrid\ViewSwitch;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $parentPK = (int)Param::get('parent_pk', false)->asInteger(false);
        $pageNumber = Param::get('structure-page', false)->asInteger(false);
        $itemsPerPage = Param::get('structure-items-per-page', false)->asInteger(false);

        $addItemUrl = '/admin/modules/structures/edit/';
        if ($parentPK != 0) {
            $addItemUrl .= "?parent_pk={$parentPK}";
        }
        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new StructureRetriever();
        $dataGrid = new DataGrid('structure', '', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);
        $dataGrid->addHiddenField('parent_pk', $parentPK);

        $dataGrid->getMenu()
            ->addElement(new Item('Добавить элемент структуры', $addItemUrl))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/structures/edit/', 'edit', '', ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/structures/delete/', 'delete', '', ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('structure-filter-id', false)->asString(false)))
            ->addHeader(new Header('name', 'Наименование', new ViewLink('/admin/modules/structures/?parent_pk={label}', false, 'id'), ['class' => 'text-center', 'style' => 'width: 300px'], [], true, Param::get('structure-filter-name', false)->asString(false)))
            ->addHeader(new Header('parent_structure_name', 'Родитель', null, ['class' => 'text-center', 'style' => 'width: 300px;'], [], true, Param::get('structure-filter-parent_structure_name', false)->asString(false), 'name', 'parent_structure'))
            ->addHeader(new Header('path', 'Путь', null, ['class' => 'text-center', 'style' => 'width: 300px;'], [], true, Param::get('structure-filter-path', false)->asString(false)))
            ->addHeader(new Header('module_alias', 'Модуль', null, ['class' => 'text-center', 'style' => 'width: 300px;'], [], true, Param::get('structure-filter-module_alias', false)->asString(false), 'alias', 'module'))
            ->addHeader(new Header('anchor', 'Фрагмент', new ViewSwitch(), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('structure-filter-anchor', false)->asString(false)))
            ->addHeader(new Header('frame', 'Фрейм', new ViewEmpty(), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('structure-filter-frame', false)->asString(false)))
            ->addHeader(new Header('seo_title', 'SEO T', new ViewEmpty(), ['class' => 'text-center', 'style' => 'width: 80px;'], ['class' => 'text-center'], true, Param::get('structure-filter-seo_title', false)->asString(false)))
            ->addHeader(new Header('seo_description', 'SEO D', new ViewEmpty(), ['class' => 'text-center', 'style' => 'width: 80px;'], ['class' => 'text-center'], true, Param::get('structure-filter-seo_description', false)->asString(false)))
            ->addHeader(new Header('seo_keywords', 'SED K', new ViewEmpty(), ['class' => 'text-center', 'style' => 'width: 80px;'], ['class' => 'text-center'], true, Param::get('structure-filter-seo_keywords', false)->asString(false)))
            ->addHeader(new Header('priority', '<span class="glyphicon glyphicon-sort-by-attributes" title="Приоритет"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('structure-filter-priority', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('structure-filter-active', false)->asString(false)))
        ;

        $structures = $retriever->getStructures(
            $parentPK,
            $dataGrid->getFilterConditions('structure'),
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
            new Breadcrumb('Структура сайта', '/structures')
        ];
        $breadcrumbsParentPK = $parentPK;
        $structureBreadcrumbs = [];
        while($breadcrumbsParentPK) {
            /** @var Structure $oParentStructure */
            $oParentStructure = DataSource::factory(Structure::cls(), $breadcrumbsParentPK);
            $structureBreadcrumbs[] = new Breadcrumb($oParentStructure->name, "?parent_pk={$oParentStructure->getPrimaryKey()}", true);
            $breadcrumbsParentPK = $oParentStructure->structure_id;
        }
        $viewBreadcrumbs->breadcrumbs = array_merge($viewBreadcrumbs->breadcrumbs, array_reverse($structureBreadcrumbs));

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $dataGridView);
        $this->frame->render();
    }

} 