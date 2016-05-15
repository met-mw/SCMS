<?php
namespace App\Modules\Structures\Models;


use App\Models\Module;
use App\Models\StructureSetting;
use SORM\DataSource;
use SORM\Entity;

/**
 * Class Structure
 * @package App\Modules\Structures\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $structure_id
 * @property string $path
 * @property string $frame
 * @property int $module_id
 * @property int $is_main
 * @property int $anchor
 * @property int $priority
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property int $active
 * @property int $deleted
 */
class Structure extends Entity
{

    protected $tableName = 'structure';

    public function getStructureSettings()
    {
        /** @var StructureSetting[] $aStructureSettings */
        $aStructureSettings = $this->findRelationCache($this->getPrimaryKeyName(), StructureSetting::cls());
        if (empty($aStructureSettings)) {
            $oStructureSettings = DataSource::factory(StructureSetting::cls());
            $oStructureSettings->builder()
                ->where("structure_id={$this->getPrimaryKey()}");

            $aStructureSettings = $oStructureSettings->findAll();
            foreach ($aStructureSettings as $oStructureSetting) {
                $this->addRelationCache('id', $oStructureSetting);
                $oStructureSetting->addRelationCache('structure_id', $this);
            }
        }

        return $aStructureSettings;
    }

    public function getModule()
    {
        /** @var Module[] $aModules */
        $aModules = $this->findRelationCache('module_id', Module::cls());
        if (empty($aModules)) {
            $oModules = DataSource::factory(Module::cls());
            $oModules->builder()
                ->where("id={$this->module_id}");

            $aModules = $oModules->findAll();
            foreach ($aModules as $oModule) {
                $oModule->addRelationCache('id', $this);
                $this->addRelationCache('module_id', $oModule);
            }
        }

        return isset($aModules[0]) ? $aModules[0] : null;
    }

    public function getStructures()
    {
        /** @var Structure[] $aStructures */
        $aStructures = $this->findRelationCache($this->getPrimaryKeyName(), Structure::cls());
        if (empty($aStructures)) {
            $oStructures = DataSource::factory(Structure::cls());
            $oStructures->builder()
                ->where("structure_id={$this->getPrimaryKey()}");

            $aStructures = $oStructures->findAll();
            foreach ($aStructures as $oStructure) {
                $this->addRelationCache($this->getPrimaryKeyName(), $oStructure);
                $oStructure->addRelationCache('structure_id', $this);
            }
        }

        return $aStructures;
    }

    public function getStructureFragments()
    {
        /** @var Structure[] $aStructures */
        $aStructures = $this->findRelationCache($this->getPrimaryKeyName(), Structure::cls());
        if (empty($aStructures)) {
            $oStructures = DataSource::factory(Structure::cls());
            $oStructures->builder()
                ->where("structure_id={$this->getPrimaryKey()}")
                ->whereAnd()
                ->where('anchor=1');

            $aStructures = $oStructures->findAll();
            foreach ($aStructures as $oStructure) {
                $this->addRelationCache('id', $oStructure);
                $oStructure->addRelationCache('structure_id', $this);
            }
        }

        return $aStructures;
    }

    public function getParentStructure()
    {
        /** @var Structure[] $aStructures */
        $aStructures = $this->findRelationCache('structure_id', Structure::cls());
        if (empty($aStructures)) {
            $oStructures = DataSource::factory(Structure::cls());
            $oStructures->builder()
                ->where("id={$this->structure_id}");

            $aStructures = $oStructures->findAll();
            foreach ($aStructures as $oStructure) {
                $oStructure->addRelationCache('id', $this);
                $this->addRelationCache('structure_id', $oStructure);
            }
        }

        return isset($aStructures[0]) ? $aStructures[0] : null;
    }

}