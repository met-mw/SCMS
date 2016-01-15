<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\StructureSetting;
use App\Modules\Structures\Models\Structure;
use App\Modules\Structures\Views\Admin\ViewStructureEdit;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewModuleConfiguration;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Entity;
use SORM\Tools\Builder;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->frame->addCss('/public/assets/css/edit-form.css');
        $this->frame->addCss('/public/assets/css/main-menu.css');

        $structureId = (int)Param::get('pk', false)->asInteger(false);
        $view = new ViewStructureEdit();

        $structureParentId = 0;
        if ($structureId != 0) {
            /** @var Structure $structure */
            $structure = DataSource::factory(Structure::cls(), $structureId);
            $view->structure = $structure;
            $view->parentId = $structure->structure_id;
            if ($structure->module_id != 0) {
                /** @var Module $module */
                $module = $structure->field('module_id')->loadRelation(Module::cls());
                $view->currentModuleConfigView = $this->getModuleConfigView($structure, $module);
            }
        } else {
            $structureParentId = (int)Param::get('parent_pk', false)->asInteger(false);
            $view->parentId = $structureParentId;
        }

        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs($structureId, $structureParentId);
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $view->modulesList = DataSource::factory(Module::cls())->findAll();
        $view->structuresList = DataSource::factory(Structure::cls())->findAll();
        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

    protected function fillBreadcrumbs($id = 0, $parentId = 0) {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Структура сайта', 'structures');
        $bcStructures = $bcModules->findChildNodeByPath('structures');

        if ($id != 0) {
            $aStructures = [];
            /** @var Structure $oStructure */
            $oStructure = DataSource::factory(Structure::cls(), $id);
            $currentName = $oStructure->name;
            while ($oStructure->structure_id != 0) {
                $oStructure = DataSource::factory(Structure::cls(), $oStructure->structure_id);
                $aStructures[] = $oStructure;
            }

            foreach (array_reverse($aStructures) as $oStructure) {
                $path = "parent_pk={$oStructure->id}";
                $bcStructures->addChildNode($oStructure->name, $path, true, false, true);
                $bcStructures = $bcStructures->findChildNodeByPath($path);
            }

            $bcStructures->addChildNode('Редкатирование', 'edit', true, true);
            $bcEdit = $bcStructures->findChildNodeByPath('edit');
            $bcEdit->addChildNode("Редактирование \"{$currentName}\"", "pk={$id}", false, false, true);
        } else {
            if ($parentId != 0) {
                $aStructures = [];
                /** @var Structure $oStructure */
                $oStructure = DataSource::factory(Structure::cls(), $parentId);
                $aStructures[] = $oStructure;
                while ($oStructure->structure_id != 0) {
                    $oStructure = DataSource::factory(Structure::cls(), $oStructure->structure_id);
                    $aStructures[] = $oStructure;
                }

                foreach (array_reverse($aStructures) as $oStructure) {
                    $path = "parent_pk={$oStructure->id}";
                    $bcStructures->addChildNode($oStructure->name, $path, true, false, true);
                    $bcStructures = $bcStructures->findChildNodeByPath($path);
                }

                $bcStructures->addChildNode('Добавление', 'edit', true, true);
                $bcEdit = $bcStructures->findChildNodeByPath('edit');
                $bcEdit->addChildNode('Добавление', $parentId == 0 ? '' : "parent_pk={$parentId}", false, false, true);
            } else {
                $bcStructures->addChildNode('Добавление', 'edit');
            }
        }
    }

    public function actionAjaxModuleConfig() {
        $this->authorizeIfNot();

        $structureId = Param::get('structure_id', false)->asInteger(false);
        $moduleId = Param::get('module_id')->asInteger();
        /** @var Structure $structure */
        $structure = DataSource::factory(Structure::cls(), $structureId == 0 ? null : $structureId);
        /** @var Module $module */
        $module = DataSource::factory(Module::cls(), $moduleId == 0 ? null : $moduleId);

        ob_start();
        $view = $this->getModuleConfigView($structure, $module);
        $view->render();
        $form = ob_get_clean();

        $this->response->send('', ['form' => $form]);
    }

    protected function getModuleConfigView(Structure $structure, Module $module) {
        /** @var Module $module */
        $moduleConfigView = new ViewModuleConfiguration();

        /** @var ModuleSetting[] $moduleSettings */
        $moduleSettings = $module->field()->loadRelation(ModuleSetting::cls());
        $settings = [];
        foreach ($moduleSettings as $moduleSetting) {
            /** @var Entity $list */
            $list = DataSource::factory($moduleSetting->entity)->findAll();
            $structureSetting = null;
            if ($structure->id) {
                /** @var StructureSetting $oStructureSettings */
                $oStructureSettings = $structure->field()->prepareRelation(StructureSetting::cls());
                $oStructureSettings->builder()
                    ->whereAnd()
                    ->where("module_setting_id={$moduleSetting->getPrimaryKey()}");
                /** @var StructureSetting[] $aStructureSettings */
                $aStructureSettings = $oStructureSettings->findAll();
                $structureSetting = reset($aStructureSettings);
            }

            $settings[] = [
                'setting' => $moduleSetting,
                'list' => $list,
                'value' => is_null($structureSetting) ? null : $structureSetting->value
            ];
        }

        $moduleConfigView->settings = $settings;

        return $moduleConfigView;
    }

} 