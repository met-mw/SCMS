<?php
namespace App\Models;


use App\Models\Structure;
use SORM\DataSource;
use SORM\Entity;

/**
 * Class Module
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property bool $allow_user_area
 * @property bool $allow_anchor
 * @property bool $active
 */
class Module extends Entity
{

    const TYPE_ENTITY = 0;
    const TYPE_LIST = 1;
    const TYPE_VALUE = 2;

    protected $tableName = 'module';

    public function getStructures()
    {
        /** @var Structure[] $aStructures */
        $aStructures = $this->findRelationCache($this->getPrimaryKeyName(), Structure::cls());
        if (empty($aStructures)) {
            $oStructures = DataSource::factory(Structure::cls());
            $oStructures->builder()
                ->where("module_id={$this->getPrimaryKey()}");

            $aStructures = $oStructures->findAll();
            foreach ($aStructures as $oStructure) {
                $this->addRelationCache($this->getPrimaryKeyName(), $oStructure);
                $oStructure->addRelationCache('module_id', $this);
            }
        }
    }

    public function getModuleSettings()
    {
        /** @var ModuleSetting[] $aModuleSettings */
        $aModuleSettings = $this->findRelationCache($this->getPrimaryKeyName(), ModuleSetting::cls());
        if (empty($aModuleSettings)) {
            $oModuleSettings = DataSource::factory(ModuleSetting::cls());
            $oModuleSettings->builder()
                ->where("module_id={$this->getPrimaryKey()}");

            $aModuleSettings = $oModuleSettings->findAll();
            foreach ($aModuleSettings as $oModuleSetting) {
                $this->addRelationCache('id', $oModuleSetting);
                $oModuleSetting->addRelationCache('module_id', $this);
            }
        }

        return $aModuleSettings;
    }

    public function getModules()
    {
        /** @var Module[] $aModules */
        $aModules = $this->findRelationCache($this->getPrimaryKeyName(), Module::cls());
        if (empty($aModules)) {
            $oModules = DataSource::factory(Module::cls());
            $oModules->builder()
                ->where("module_id={$this->getPrimaryKey()}");

            $aModules = $oModules->findAll();
            foreach ($aModules as $oModule) {
                $this->addRelationCache($this->getPrimaryKeyName(), $oModule);
                $oModule->addRelationCache('module_id', $this);
            }
        }
    }

}