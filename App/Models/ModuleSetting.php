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

}