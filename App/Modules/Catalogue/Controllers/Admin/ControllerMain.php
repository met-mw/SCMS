<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Classes\Retrievers\CatalogueRetriever;
use App\Modules\Catalogue\Models\Category;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewChange;
use SFramework\Views\DataGrid\ViewCondition;
use SFramework\Views\DataGrid\ViewDefault;
use SFramework\Views\DataGrid\ViewImage;
use SFramework\Views\DataGrid\ViewLink;
use SFramework\Views\DataGrid\ViewMoney;
use SFramework\Views\DataGrid\ViewStub;
use SFramework\Views\DataGrid\ViewSwitch;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $parentCategoryId = Param::get('parent_pk', false);
        if ($parentCategoryId->exists()) {
            $parentCategoryId = $parentCategoryId->asInteger(true, 'Недопустимое значение параметра!');
            /** @var Category $oParentCategoryFact */
            $oParentCategoryFact = DataSource::factory(Category::cls(), $parentCategoryId);
            if ($oParentCategoryFact->isNew()) {
                NotificationLog::instance()->pushError('Недопустимое значение параметра!');
                $this->frame->render();
                return;
            }
        } else {
            $parentCategoryId = 0;
        }
        $pageNumber = Param::get('catalogue-page', false)->asInteger(false);
        $itemsPerPage = Param::get('catalogue-items-per-page', false)->asInteger(false);

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $dataGridView = new ViewDataGrid();
        $retriever = new CatalogueRetriever();
        $dataGrid = new DataGrid('catalogue', '/admin/modules/catalogue/', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);

        $dataGrid->getMenu()
            ->addElement(new DataGrid\Menu\Item('Добавить категорию', '/admin/modules/catalogue/edit/?is_category=1' . ($parentCategoryId ? "&parent_pk={$parentCategoryId}" : '')))
            ->addElement(new DataGrid\Menu\Item('Добавить позицию', '/admin/modules/catalogue/edit/?is_category=0' . ($parentCategoryId ? "&parent_pk={$parentCategoryId}" : '')))
        ;

        $dataGrid
            ->addAction(new Action('id', '/admin/modules/catalogue/edit/', 'edit', '', ['is_category'], ['class' => 'glyphicon glyphicon-pencil'], 'Редактировать'))
            ->addAction(new Action('id', '/admin/modules/catalogue/delete/', 'delete', '', ['is_category'], ['class' => 'glyphicon glyphicon-trash'], 'Удалить', true))
        ;

        $dataGrid
            ->addHeader(new Header('id', '№', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-id', false)->asString(false)))
            ->addHeader(new Header('is_category', 'Тип', new ViewChange('', [[0, '<span class="glyphicon glyphicon-file"></span>'], [1, '<span class="glyphicon glyphicon-folder-open"></span>']]), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-is_category', false)->asString(false)))
            ->addHeader(new Header('name', 'Наименование', new ViewCondition(new ViewDefault(), [['field' => 'is_category', 'value' => 1, 'view' => new ViewLink('/admin/modules/catalogue/?parent_pk={label}', false, 'id')]]), ['class' => 'text-center', 'style' => 'width: 300px'], [], true, Param::get('catalogue-filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', null, ['class' => 'text-center'], [], true, Param::get('catalogue-filter-description', false)->asString(false)))
            ->addHeader(new Header('category', 'Категория', null, ['class' => 'text-center', 'style' => 'width: 300px;'], [], true, Param::get('catalogue-filter-category', false)->asString(false)))
            ->addHeader(new Header('thumbnail', 'Миниатюра', new ViewCondition(new ViewImage(['class' => 'img-circle', 'style' => 'height: 20px;']), [['field' => 'thumbnail', 'value' => '/public/assets/images/system/no-image.svg', 'view' => new ViewStub('Нет миниатюры')]]), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-name', false)->asString(false)))
            ->addHeader(new Header('price', '<span class="glyphicon glyphicon-ruble" title="Цена"></span>', new ViewMoney('<span class="glyphicon glyphicon-ruble"></span>'), ['class' => 'text-center', 'style' => 'width: 100px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-price', false)->asString(false)))
            ->addHeader(new Header('priority', '<span class="glyphicon glyphicon-sort-by-attributes" title="Приоритет"></span>', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-priority', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-active', false)->asString(false)))
        ;

        $categoriesAndItems = $retriever->getCategoriesAndItems(
            $parentCategoryId,
            $dataGrid->getFilterConditions('childs'),
            $dataGrid->pagination->getLimit(),
            $dataGrid->pagination->getOffset()
        );

        $dataSet = new ArrayDataSet($categoriesAndItems);
        $dataGrid->addDataSet($dataSet);
        $dataGridView->dataGrid = $dataGrid;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Каталог', '/catalogue')
        ];
        $breadcrumbsParentPK = $parentCategoryId;
        $categoryBreadcrumbs = [];
        while($breadcrumbsParentPK) {
            /** @var Category $oParentCategory */
            $oParentCategory = DataSource::factory(Category::cls(), $breadcrumbsParentPK);
            $categoryBreadcrumbs[] = new Breadcrumb($oParentCategory->name, "?parent_pk={$oParentCategory->getPrimaryKey()}", true);
            $breadcrumbsParentPK = $oParentCategory->category_id;
        }
        $viewBreadcrumbs->breadcrumbs = array_merge($viewBreadcrumbs->breadcrumbs, array_reverse($categoryBreadcrumbs));

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $dataGridView);
        $this->frame->render();
    }

} 