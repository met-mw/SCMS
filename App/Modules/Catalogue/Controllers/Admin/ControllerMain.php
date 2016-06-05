<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Catalogue\Classes\Retrievers\CatalogueRetriever;
use App\Modules\Catalogue\Models\Category;
use App\Views\Admin\DataGrid\ViewDataGrid;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\DataGrid;
use SFramework\Classes\DataGrid\Action;
use SFramework\Classes\DataGrid\DataSet\ArrayDataSet;
use SFramework\Classes\DataGrid\Header;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SFramework\Views\DataGrid\ViewChange;
use SFramework\Views\DataGrid\ViewCondition;
use SFramework\Views\DataGrid\ViewCutString;
use SFramework\Views\DataGrid\ViewDefault;
use SFramework\Views\DataGrid\ViewImageLink;
use SFramework\Views\DataGrid\ViewLink;
use SFramework\Views\DataGrid\ViewMoney;
use SFramework\Views\DataGrid\ViewStub;
use SFramework\Views\DataGrid\ViewSwitch;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $this->needAuthenticate();

        $parentCategoryId = Param::get('parent_pk', false);
        if ($parentCategoryId->exists()) {
            $parentCategoryId = $parentCategoryId->asInteger(true, 'Недопустимое значение параметра!');
            /** @var Category $oParentCategoryFact */
            $oParentCategoryFact = DataSource::factory(Category::cls(), $parentCategoryId);
            if ($oParentCategoryFact->isNew()) {
                SCMSNotificationLog::instance()->pushError('Недопустимое значение параметра!');
                $this->Frame->render();
                return;
            }
        } else {
            $parentCategoryId = 0;
        }
        $pageNumber = Param::get('catalogue-page', false)->asInteger(false);
        $itemsPerPage = Param::get('catalogue-items-per-page', false)->asInteger(false);

        $manifest = $this->ModuleInstaller->getManifest($this->ModuleDirectory);

        $dataGridView = new ViewDataGrid();
        $retriever = new CatalogueRetriever();
        $dataGrid = new DataGrid('catalogue', '/admin/modules/catalogue/', 'id', $manifest['meta']['alias'], $pageNumber, $itemsPerPage, $manifest['meta']['description']);
        $dataGrid->addHiddenField('parent_pk', $parentCategoryId);

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
            ->addHeader(new Header('name', 'Наименование', new ViewCondition(new ViewDefault(), [['field' => 'is_category', 'value' => 1, 'view' => new ViewLink('/admin/modules/catalogue/?parent_pk={label}', false, 'id')]]), ['class' => 'text-center'], [], true, Param::get('catalogue-filter-name', false)->asString(false)))
            ->addHeader(new Header('description', 'Описание', new ViewCutString(20, true, ['class' => 'content-to-modal', 'style' => 'cursor: pointer;'], ['style' => 'display: none;']), ['class' => 'text-center'], ['class' => 'modal-display-field'], true, Param::get('catalogue-filter-description', false)->asString(false)))
            ->addHeader(new Header('thumbnail', 'Миниатюра', new ViewCondition(new ViewImageLink(true, ['class' => 'fancybox'], ['class' => 'img-rounded', 'style' => 'height: 20px;']), [['field' => 'thumbnail', 'value' => '/public/assets/images/system/no-image.svg', 'view' => new ViewStub('<span class="glyphicon glyphicon-picture"></span>')]]), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center']))
            ->addHeader(new Header('price', '<span class="glyphicon glyphicon-ruble" title="Цена"></span>', new ViewMoney('<span class="glyphicon glyphicon-ruble"></span>'), ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('catalogue-filter-price', false)->asString(false)))
            ->addHeader(new Header('count', '<span class="glyphicon glyphicon-inbox" title="Количество"></span>', null, ['class' => 'text-center'], ['class' => 'text-center'], true, Param::get('catalogue-filter-count', false)->asString(false)))
            ->addHeader(new Header('priority', '<span class="glyphicon glyphicon-sort-by-attributes" title="Приоритет"></span>', null, ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-priority', false)->asString(false)))
            ->addHeader(new Header('active', '<span class="glyphicon glyphicon-asterisk" title="Активность"></span>', new ViewSwitch(), ['class' => 'text-center', 'style' => 'width: 50px;'], ['class' => 'text-center'], true, Param::get('catalogue-filter-active', false)->asString(false)))
        ;

        $categoriesAndItems = $retriever->getCategoriesAndItems(
            $parentCategoryId,
            $dataGrid->getFilterConditions('childs'),
            $dataGrid->Pagination->getLimit(),
            $dataGrid->Pagination->getOffset()
        );

        $dataSet = new ArrayDataSet($categoriesAndItems);
        $dataGrid->addDataSet($dataSet);
        $dataGridView->dataGrid = $dataGrid;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Каталог', '/modules/catalogue')
        ];
        $breadcrumbsParentPK = $parentCategoryId;
        $categoryBreadcrumbs = [];
        while($breadcrumbsParentPK) {
            /** @var Category $oParentCategory */
            $oParentCategory = DataSource::factory(Category::cls(), $breadcrumbsParentPK);
            $categoryBreadcrumbs[] = new Breadcrumb($oParentCategory->name, "?parent_pk={$oParentCategory->getPrimaryKey()}", true);
            $breadcrumbsParentPK = $oParentCategory->category_id;
        }
        $viewBreadcrumbs->Breadcrumbs = array_merge($viewBreadcrumbs->Breadcrumbs, array_reverse($categoryBreadcrumbs));

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $dataGridView);
        $this->Frame->render();
    }

} 