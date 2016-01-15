<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\StructureSetting;
use App\Modules\Structures\Classes\Helpers\StructureHelper;
use App\Modules\Structures\Models\Structure;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerSave extends MasterAdminController{

    public function actionIndex() {
        $this->authorizeIfNot();

        $helper = new StructureHelper(SFW_APP_ROOT . 'Controllers' . DIRECTORY_SEPARATOR);

        $structureId = Param::post('structure-id', false)->asInteger(false);

        $name = Param::post('structure-name')->noEmpty('Заполните поле "Наименование"')->asString();
        $description = Param::post('structure-description')->asString();
        $parent = Param::post('structure-parent')->asInteger();
        $path = Param::post('structure-path')->noEmpty('Заполните поле "Путь"')->asString();
        $frame = Param::post('structure-frame')->asString();
        $module = Param::post('structure-module')->asInteger();
        $anchor = (int)Param::post('structure-anchor', false)->exists();
        $priority = Param::post('structure-priority', false)->asString();
        $active = (int)Param::post('structure-active', false)->exists();

        $accept = Param::post('structure-accept', false);

        if (NotificationLog::instance()->hasProblems()) {
            $this->response->send();
            return;
        }

        /** @var Structure $structure */
        $structure = DataSource::factory(Structure::cls(), $structureId == 0 ? null : $structureId);
        $oldPath = $helper->getPath($structure);

        $structure->name = $name;
        $structure->description = $description;
        $structure->structure_id = $parent;
        $structure->path = $path;
        $structure->frame = $frame;
        $structure->module_id = $module;
        $structure->anchor = $anchor;
        $structure->priority = $priority;
        $structure->active = $active;

        $structure->commit();
        $this->applyStructureSettings($structure);

        if ($structureId != 0) {
            $helper->removeProxyController($oldPath);
        }
        $helper->createProxyController($helper->getPath($structure));

        if (!NotificationLog::instance()->hasProblems()) {
            NotificationLog::instance()->pushMessage("Структура \"{$structure->name}\" успешно " . ($structureId == 0 ? 'добавлена' : 'отредактирована') . ".");
        }

        $redirect = "/admin/modules/structures/edit/?pk={$structure->getPrimaryKey()}";
        if ($accept->exists()) {
            $redirect = '/admin/modules/structures/' . ($structure->structure_id == 0 ? '' : "?parent_pk={$structure->structure_id}");
        } elseif ($structureId != 0) {
            $redirect = '';
        }

        $this->response->send($redirect);
    }

    protected function applyStructureSettings(Structure $structure) {
        if (is_null($structure->module_id)) {
            return;
        }

        /** @var Module $module */
        $module = DataSource::factory(Module::cls(), $structure->module_id == 0 ? null : $structure->module_id);
        /** @var ModuleSetting[] $moduleSettings */
        $moduleSettings = $module->field()->loadRelation(ModuleSetting::cls());

        /** @var StructureSetting[] $structureSettings */
        $structureSettings = $structure->field()->loadRelation(StructureSetting::cls());

        foreach ($moduleSettings as $oModuleSetting) {
            $changed = false;
            foreach ($structureSettings as $oStructureSetting) {
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
                $oNewStructureSetting->structure_id = $structure->id;
                $oNewStructureSetting->module_setting_id = $oModuleSetting->id;
                $oNewStructureSetting->value = is_null($oModuleSetting->entity)
                    ? Param::post($oModuleSetting->parameter)->asString()
                    : Param::post($oModuleSetting->parameter)->asInteger();
                $oNewStructureSetting->commit();
            }
        }
    }

} 