<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\StructureSetting;
use App\Models\Structure;
use App\Modules\Structures\Views\Admin\ViewStructureEdit;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewModuleConfiguration;
use SFramework\Classes\Breadcrumb;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Entity;
use SORM\Tools\Builder;

class ControllerEdit extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $this->Frame->addCss('/public/assets/css/edit-form.css');
        $this->Frame->addCss('/public/assets/css/main-menu.css');

        $structureId = (int)Param::get('id', false)->asInteger(false);
        $view = new ViewStructureEdit();

        $structureParentId = 0;
        if ($structureId != 0) {
            /** @var Structure $oStructure */
            $oStructure = DataSource::factory(Structure::cls(), $structureId);
            $view->structure = $oStructure;
            $view->parentId = $oStructure->structure_id;
            if ($oStructure->module_id != 0) {
                $oModule = $oStructure->getModule();
                $view->currentModuleConfigView = $this->getModuleConfigView($oStructure, $oModule);
            }
        } else {
            $structureParentId = (int)Param::get('parent_pk', false)->asInteger(false);
            $view->parentId = $structureParentId;
        }

        $view->modulesList = DataSource::factory(Module::cls())->findAll();
        $view->structuresList = DataSource::factory(Structure::cls())->findAll();

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Структура сайта', '/modules/structures')
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
        $viewBreadcrumbs->Breadcrumbs = array_merge($viewBreadcrumbs->Breadcrumbs, array_reverse($structureBreadcrumbs));
        if ($structureId && isset($oStructure)) {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb("Редактирование \"$oStructure->name\"", "?pk={$structureId}");
        } else {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb('Добавление новой структуры', '');
        }

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

    public function actionAjaxModuleConfig() {
        $this->authorizeIfNot();

        $structureId = Param::get('structure_id')
            ->noEmpty('Пропущен обязательный параметр "structure_id".')
            ->asInteger(true, '"structure_id" должен быть числом.');
        if ($structureId == 0) {
            SCMSNotificationLog::instance()->pushError("Не указана целевая структура.");
            $this->Response->send();
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

        $this->Response->send('', ['form' => $form]);
    }

    protected function getModuleConfigView(Structure $oStructure, Module $oModule) {
        $moduleConfigView = new ViewModuleConfiguration();

        $settings = [];
        if ($oModule->getPrimaryKey()) {
            $aModulesSettings = $oModule->getModuleSettings();
            foreach ($aModulesSettings as $oModuleSetting) {
                $type = Module::TYPE_LIST;
                if (!is_null($oModuleSetting->entity)) {
                    $oEntities = DataSource::factory($oModuleSetting->entity);
                    $oEntities->builder()
                        ->where('deleted=0');

                    $list = $oEntities->findAll();
                    $type = Module::TYPE_ENTITY;
                } elseif (!is_null($oModuleSetting->list)) {
                    $list = json_decode($oModuleSetting->list, true);
                } else {
                    $list = [];
                }

                $oStructureSetting = null;
                if ($oStructure->id) {
                    $oStructureSettings = DataSource::factory(StructureSetting::cls());
                    $oStructureSettings->builder()
                        ->where("structure_id={$oStructure->getPrimaryKey()}")
                        ->whereAnd()
                        ->where("module_setting_id={$oModuleSetting->getPrimaryKey()}");

                    /** @var StructureSetting[] $aStructureSettings */
                    $aStructureSettings = $oStructureSettings->findAll();
                    if (!empty($aStructureSettings)) {
                        $oStructureSetting = $aStructureSettings[0];
                    }
                }

                $settings[] = [
                    'type' => $type,
                    'setting' => $oModuleSetting,
                    'list' => $list,
                    'value' => is_null($oStructureSetting) ? null : $oStructureSetting->value
                ];
            }
        }
        $moduleConfigView->settings = $settings;

        return $moduleConfigView;
    }

} 