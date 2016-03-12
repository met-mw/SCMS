<?php
namespace App\Modules\Catalogue\Controllers\Admin\Category;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Views\Admin\ViewCategoryEdit;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $view = new ViewCategoryEdit();
        $view->parentId = 0;
        $view->category = DataSource::factory(Category::cls());
        $this->frame->bindView('content', $view);

//        $this->frame->addCss('/public/assets/css/edit-form.css');
//        $this->frame->addCss('/public/assets/css/main-menu.css');
//
//        $catalogueId = (int)Param::get('pk', false)->asInteger(false);
//        $view = new ViewStructureEdit();
//
//        $structureParentId = 0;
//        if ($structureId != 0) {
//            /** @var Structure $structure */
//            $structure = DataSource::factory(Structure::cls(), $structureId);
//            $view->structure = $structure;
//            $view->parentId = $structure->structure_id;
//            if ($structure->module_id != 0) {
//                /** @var Module $module */
//                $module = $structure->field('module_id')->loadRelation(Module::cls());
//                $view->currentModuleConfigView = $this->getModuleConfigView($structure, $module);
//            }
//        } else {
//            $structureParentId = (int)Param::get('parent_pk', false)->asInteger(false);
//            $view->parentId = $structureParentId;
//        }
//
//        $this->buildBreadcrumbs();
//        $this->fillBreadcrumbs($structureId, $structureParentId);
//        $viewBreadcrumbs = new ViewBreadcrumbs();
//        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
//        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
//
//        $view->modulesList = DataSource::factory(Module::cls())->findAll();
//        $view->structuresList = DataSource::factory(Structure::cls())->findAll();
//        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

} 