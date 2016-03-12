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
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Entity;
use SORM\Tools\Builder;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->frame->addCss('/public/assets/css/edit-form.css');
        $this->frame->addCss('/public/assets/css/main-menu.css');

        $structureId = (int)Param::get('id', false)->asInteger(false);
        $view = new ViewStructureEdit();

        $structureParentId = 0;
        if ($structureId != 0) {
            /** @var Structure $oStructure */
            $oStructure = DataSource::factory(Structure::cls(), $structureId);
            $view->structure = $oStructure;
            $view->parentId = $oStructure->structure_id;
            if ($oStructure->module_id != 0) {
                /** @var Module $module */
                $module = $oStructure->field('module_id')->loadRelation(Module::cls());
                $view->currentModuleConfigView = $this->getModuleConfigView($oStructure, $module);
            }
        } else {
            $structureParentId = (int)Param::get('parent_pk', false)->asInteger(false);
            $view->parentId = $structureParentId;
        }

        $view->modulesList = DataSource::factory(Module::cls())->findAll();
        $view->structuresList = DataSource::factory(Structure::cls())->findAll();

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Структура сайта', '/structures')
        ];

        if ($structureParentId) {
            $breadcrumbsParentPK = $structureParentId;
        } elseif (isset($oStructure)) {
            $breadcrumbsParentPK = $oStructure->structure_id;
        } else {
            $breadcrumbsParentPK = 0;
        }
        $structureBreadcrumbs = [];
        while($breadcrumbsParentPK) {
            /** @var Structure $oParentStructure */
            $oParentStructure = DataSource::factory(Structure::cls(), $breadcrumbsParentPK);
            $structureBreadcrumbs[] = new Breadcrumb($oParentStructure->name, "?parent_pk={$oParentStructure->getPrimaryKey()}", true);
            $breadcrumbsParentPK = $oParentStructure->structure_id;
        }
        $viewBreadcrumbs->breadcrumbs = array_merge($viewBreadcrumbs->breadcrumbs, array_reverse($structureBreadcrumbs));
        if ($structureId && isset($oStructure)) {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb("Редактирование \"$oStructure->name\"", "?pk={$structureId}");
        } else {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb('Добавление новой структуры', '');
        }

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

    public function actionAjaxModuleConfig() {
        $this->authorizeIfNot();

        $structureId = Param::get('structure_id')
            ->noEmpty('Пропущен обязательный параметр "structure_id".')
            ->asInteger(true, '"structure_id" должен быть числом.');
        if ($structureId == 0) {
            NotificationLog::instance()->pushError("Не указана целевая структура.");
            $this->response->send();
            exit;
        }

        $moduleId = Param::get('module_id')
            ->noEmpty('Пропущен обязательный параметр "module_id".')
            ->asInteger(true, '"structure_id" должен быть числом.');

        /** @var Structure $oStructure */
        $oStructure = DataSource::factory(Structure::cls(), $structureId);
        /** @var Module $oModule */
        $oModule = DataSource::factory(Module::cls(), $moduleId == 0 ? null : $moduleId);

        ob_start();
        $view = $this->getModuleConfigView($oStructure, $oModule);
        $view->render();
        $form = ob_get_clean();

        $this->response->send('', ['form' => $form]);
    }

    protected function getModuleConfigView(Structure $oStructure, Module $oModule) {
        $moduleConfigView = new ViewModuleConfiguration();

        $settings = [];
        if ($oModule->getPrimaryKey()) {
            /** @var ModuleSetting[] $moduleSettings */
            $moduleSettings = $oModule->field()->loadRelation(ModuleSetting::cls());
            foreach ($moduleSettings as $moduleSetting) {
                /** @var Entity $list */
                $list = DataSource::factory($moduleSetting->entity)->findAll();
                $oStructureSetting = null;
                if ($oStructure->id) {
                    /** @var StructureSetting $oStructureSettings */
                    $oStructureSettings = $oStructure->field()->prepareRelation(StructureSetting::cls());
                    $oStructureSettings->builder()
                        ->whereAnd()
                        ->where("module_setting_id={$moduleSetting->getPrimaryKey()}");
                    /** @var StructureSetting[] $aStructureSettings */
                    $aStructureSettings = $oStructureSettings->findAll();
                    if (!empty($aStructureSettings)) {
                        $oStructureSetting = $aStructureSettings[0];
                    }
                }

                $settings[] = [
                    'setting' => $moduleSetting,
                    'list' => $list,
                    'value' => is_null($oStructureSetting) ? null : $oStructureSetting->value
                ];
            }
        }
        $moduleConfigView->settings = $settings;

        return $moduleConfigView;
    }

} 