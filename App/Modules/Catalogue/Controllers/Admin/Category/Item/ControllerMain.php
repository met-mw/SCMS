<?php
namespace App\Modules\Catalogue\Controllers\Admin\Category\Item;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Models\Catalogue;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\Decorations\ViewCallback;
use App\Views\Admin\Entities\Decorations\ViewLink;
use App\Views\Admin\Entities\Decorations\ViewParent;
use App\Views\Admin\Entities\ViewList;
use Exception;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $catalogueId = Param::get('catalogue_pk')->asInteger(true, 'Не выбран каталог!');
        if (is_null($catalogueId)) {
            throw new Exception("Не задан обязательный параметр!");
        }

        $categoryId = Param::get('category_pk', false);
        if ($categoryId->exists()) {
            $categoryId = $categoryId->asInteger(true, 'Недопустимое значение параметра!');
        } else {
            $categoryId = 0;
        }

        $view = new ViewList();
        $addItemUrl = "/admin/modules/catalogue/category/item/edit/";
        $view->menu->addItem('Добавить', $addItemUrl, 'glyphicon-plus');

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $view->table->caption = $manifest['meta']['alias'];
        $view->table->description = $manifest['meta']['description'];

        $view->table
            ->addAction('Редактирование', '/admin/modules/catalogue/category/item/edit/', 'glyphicon-pencil')
            ->addAction('Удалить', '/admin/modules/catalogue/category/item/delete/', 'glyphicon-trash', false, ['entity-delete'])
        ;

        /** @var Category $oCategory */
        $oCategory = DataSource::factory(Category::cls(), $categoryId);
        /** @var Item $oItems */
        $oItems = $oCategory->field()->prepareRelation(Item::cls());
        $oItems->builder()
            ->sqlCalcFoundRows()
            ->order('priority');

        if ($this->pager) {
            $oItems->builder()
                ->limit($this->pager->getLimit())
                ->offset($this->pager->getOffset());
        }

        /** @var Item[] $aItems */
        $aItems = $oItems->findAll();
        if ($this->pager) {
            $this->pager->prepare();
        }
        $view->table->tableBody->data = $aItems;

        foreach($oItems->getFieldsDisplayNames() as $name => $displayName) {
            $view->table->addColumn($name, $displayName);
        }

        $view->table->tableBody->addDecoration('active', new ViewActive('active'));

        $view->table->tableBody->addDecoration('category_id',
            new ViewCallback(
                'category_id',
                function($categoryId) {
                    if ($categoryId == 0) {
                        return '';
                    }

                    /** @var Category $oCategory */
                    $oCategory = DataSource::factory(Category::cls(), $categoryId);
                    return $oCategory->name;
                }
            )
        );
        $view->table->tableBody->addDecoration('catalogue_id',
            new ViewCallback(
                'catalogue_id',
                function($catalogueId) {
                    if ($catalogueId == 0) {
                        return '';
                    }

                    /** @var Catalogue $oCatalogue */
                    $oCatalogue = DataSource::factory(Category::cls(), $catalogueId);
                    return $oCatalogue->name;
                }
            )
        );

        if ($this->pager) {
            $this->fillPager($view);
        }


//        $this->buildBreadcrumbs();
//        $this->fillBreadcrumbs($parentPK);
//        $viewBreadcrumbs = new ViewBreadcrumbs();
//        $this->breadcrumbs->setIgnores(['page', 'back_params']);
//        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
//        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

} 