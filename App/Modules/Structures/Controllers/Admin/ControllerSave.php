<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Classes\Helpers\StructureHelper;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\StructureSetting;
use App\Modules\Structures\Classes\Retrievers\StructureRetriever;
use App\Models\Structure;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerSave extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $StructureHelper = new StructureHelper(SFW_APP_ROOT . 'Controllers' . DIRECTORY_SEPARATOR);

        $structureId = Param::post('structure-id', false)->asInteger(false);

        $name = Param::post('structure-name')->noEmpty('Заполните поле "Наименование"')->asString();
        $description = Param::post('structure-description')->asString();
        $parent = Param::post('structure-parent')->asInteger(true, 'Поле "Родительский раздел" заполнено неверно.');
        $path = Param::post('structure-path')->asString();
        $frame = Param::post('structure-frame')->asString();
        $module = Param::post('structure-module')->asInteger(true, 'Поле "Модуль" заполнено неверно.');
        $anchor = (int)Param::post('structure-anchor', false)->exists();
        $priority = Param::post('structure-priority', false)->asString();
        $isMain = (int)Param::post('structure-is-main', false)->exists();
        $active = (int)Param::post('structure-active', false)->exists();
        $seoTitle = Param::post('structure-seo-title', false)->asString();
        $seoDescription = Param::post('structure-seo-description', false)->asString();
        $seoKeywords = Param::post('structure-seo-keywords', false)->asString();

        $accept = Param::post('structure-accept', false);

        if (SCMSNotificationLog::instance()->hasProblems()) {
            $this->Response->send();
            return;
        }

        /** @var Structure $oStructure */
        $oStructure = DataSource::factory(Structure::cls(), $structureId == 0 ? null : $structureId);
        $oldPath = $StructureHelper->getPath($oStructure);

        $oStructure->name = $name;
        $oStructure->description = $description;
        $oStructure->structure_id = $parent;
        $oStructure->path = $path;
        $oStructure->frame = $frame;
        $oStructure->module_id = $module;
        $oStructure->anchor = $anchor;
        $oStructure->priority = $priority;
        $oStructure->is_main = $isMain;
        $oStructure->active = $active;
        $oStructure->seo_title = $seoTitle;
        $oStructure->seo_description = $seoDescription;
        $oStructure->seo_keywords = $seoKeywords;
        if (!$oStructure->getPrimaryKey()) {
            $oStructure->deleted = false;
        }

        if ($isMain) {
            $retriever = new StructureRetriever();
            $retriever->clearMainFlag();
        }

        $oStructure->commit();
        $this->applyStructureSettings($oStructure);

        if ($structureId != 0) {
            $StructureHelper->removeProxyController($oldPath);
        }
        $StructureHelper->createProxyController($StructureHelper->getPath($oStructure));

        if (!SCMSNotificationLog::instance()->hasProblems()) {
            SCMSNotificationLog::instance()->pushMessage("Структура \"{$oStructure->name}\" успешно " . ($structureId == 0 ? 'добавлена' : 'отредактирована') . ".");
        }

        $redirect = "/admin/modules/structures/edit/?pk={$oStructure->getPrimaryKey()}";
        if ($accept->exists()) {
            $redirect = '/admin/modules/structures/' . ($oStructure->structure_id == 0 ? '' : "?parent_pk={$oStructure->structure_id}");
        } elseif ($structureId != 0) {
            $redirect = '';
        }

        $this->Response->send($redirect);
    }

    protected function applyStructureSettings(Structure $oStructure)
    {
        if (!$oStructure->module_id) {
            return;
        }

        /** @var Module $oModule */
        $oModule = $oStructure->getModule();
        /** @var ModuleSetting[] $aModuleSettings */
        $aModuleSettings = $oModule->getModuleSettings();

        /** @var StructureSetting[] $aStructureSettings */
        $aStructureSettings = $oStructure->getStructureSettings();

        foreach ($aModuleSettings as $oModuleSetting) {
            $changed = false;
            foreach ($aStructureSettings as $oStructureSetting) {
                if ($oModuleSetting->module_id == $oStructureSetting->module_setting_id) {
                    $oStructureSetting->value = is_null($oModuleSetting->entity)
                        ? (string)Param::post($oModuleSetting->parameter, false)->asString()
                        : (string)Param::post($oModuleSetting->parameter, false)->asInteger();
                    $oStructureSetting->commit();
                    $changed = true;
                }
            }

            if (!$changed) {
                /** @var StructureSetting $oNewStructureSetting */
                $oNewStructureSetting = DataSource::factory(StructureSetting::cls());
                $oNewStructureSetting->structure_id = $oStructure->id;
                $oNewStructureSetting->module_setting_id = $oModuleSetting->id;
                $oNewStructureSetting->value = is_null($oModuleSetting->entity)
                    ? Param::post($oModuleSetting->parameter)->asString()
                    : Param::post($oModuleSetting->parameter)->asInteger();
                $oNewStructureSetting->commit();
            }
        }
    }

} 