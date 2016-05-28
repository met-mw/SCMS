<?php
namespace App\Models;


use App\Models\Structure;
use SORM\DataSource;
use SORM\Entity;

/**
 * Class StructureSetting
 * @package App\Models
 *
 * @property int $id;
 * @property int $structure_id;
 * @property int $module_setting_id;
 * @property string $value;
 */
class StructureSetting extends Entity {

    protected $tableName = 'structure_setting';

    public function getStructure()
    {
        /** @var Structure[] $aStructures */
        $aStructures = $this->findRelationCache('structure_id', Structure::cls());
        if (empty($aStructures)) {
            $oStructures = DataSource::factory(Structure::cls());
            $oStructures->builder()
                ->where("id={$this->structure_id}");

            $aStructures = $oStructures->findAll();
            foreach ($aStructures as $oStructure) {
                $this->addRelationCache('structure_id', $oStructure);
                $oStructure->addRelationCache($oStructure->getPrimaryKeyName(), $this);
            }
        }

        return isset($aStructures[0]) ? $aStructures[0] : null;
    }

    public function getModuleSetting()
    {
        /** @var ModuleSetting[] $aModuleSettings */
        $aModuleSettings = $this->findRelationCache('module_setting_id', ModuleSetting::cls());
        if (empty($aModuleSettings)) {
            $oModuleSettings = DataSource::factory(ModuleSetting::cls());
            $oModuleSettings->builder()
                ->where("{$oModuleSettings->getPrimaryKeyName()}={$this->module_setting_id}");

            $aModuleSettings = $oModuleSettings->findAll();
            foreach ($aModuleSettings as $oModuleSetting) {
                $this->addRelationCache('module_setting_id', $oModuleSetting);
                $oModuleSetting->addRelationCache($oModuleSetting->getPrimaryKeyName(), $this);
            }
        }

        return isset($aModuleSettings[0]) ? $aModuleSettings[0] : null;
    }

}