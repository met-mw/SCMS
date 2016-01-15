<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Pages\Models\Page;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\ViewList;
use App\Views\Admin\ViewBreadcrumbs;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewList();
        $view->menu->addItem('Добавить', '/admin/modules/pages/edit/', 'glyphicon-plus');

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $view->table->caption = $manifest['meta']['alias'];
        $view->table->description = $manifest['meta']['description'];
        $view->table
            ->addAction('Редактирование', '/admin/modules/' . strtolower($this->moduleName) . '/edit/', 'glyphicon-pencil')
            ->addAction('Удалить', '/admin/modules/' . strtolower($this->moduleName) . '/delete/', 'glyphicon-trash', false, ['entity-delete'])
        ;

        /** @var Page $pages */
        $pages = DataSource::factory(Page::cls());
        $pages->builder()->sqlCalcFoundRows();
        if ($this->pager) {
            $pages->builder()
                ->limit($this->pager->getLimit())
                ->offset($this->pager->getOffset());
        }
        $view->table->tableBody->data = $pages->findAll();
        if ($this->pager) {
            $this->pager->prepare();
        }
        $view->table
            ->addColumn('id', '№')
            ->addColumn('name', 'Наименование')
            ->addColumn('description', 'Описание')
            ->addColumn('active', 'Активна')
        ;

        $view->table->tableBody->addDecoration('active', new ViewActive('active'));
        if ($this->pager) {
            $this->fillPager($view);
        }


        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs();
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $this->breadcrumbs->setIgnores(['page']);
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

    protected function fillBreadcrumbs() {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Статичные страницы', 'pages');
    }

} 