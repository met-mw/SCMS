<?php
namespace App\Models;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class ModuleSetting
 * @package App\Models
 *
 * @property int $id;
 * @property int $module_id;
 * @property string $parameter;
 * @property string $entity;
 * @property string $alias;
 * @property string $description;
 */
class ModuleSetting extends Entity {

    protected $tableName = 'modules_setting';

    public function prepareRelations() {
        $this->field('module_id')->addRelationMTO(DataSource::factory(Module::cls()));
    }

    public function getModule()
    {
        /** @var Module[] $aModules */
        $aModules = $this->findRelationCache('module_id', Module::cls());
        if (empty($aModules)) {
            $oModules = DataSource::factory(Module::cls());
            $oModules->builder()
                ->where("{$oModules->getPrimaryKeyName()}={$this->module_id}");

            $aModules = $oModules->findAll();
            foreach ($aModules as $oModule) {
                $this->addRelationCache('module_id', $oModule);
                $oModule->addRelationCache($oModule->getPrimaryKeyName(), $this);
            }
        }

        return isset($aModules[0]) ? $aModules[0] : null;
    }

}