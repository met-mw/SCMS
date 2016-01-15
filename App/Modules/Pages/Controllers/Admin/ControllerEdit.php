<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageId = Param::get('pk', false)->asInteger();

        $view = new ViewEditForm();
        $view->page = is_null($pageId) ? null : DataSource::factory(Page::cls(), $pageId);

        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs($view->page);
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

    protected function fillBreadcrumbs(Page $page = null) {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Статичные страницы', 'pages');
        $bcPages = $bcModules->findChildNodeByPath('pages');
        if (is_null($page)) {
            $bcPages->addChildNode('Добавление', 'edit');
        } else {
            $bcPages->addChildNode('Редкатирование', 'edit', true, true);
            $bcEdit = $bcPages->findChildNodeByPath('edit');
            $bcEdit->addChildNode("Редкатирование \"{$page->name}\"", "pk={$page->getPrimaryKey()}", false, false, true);
        }
    }

} 