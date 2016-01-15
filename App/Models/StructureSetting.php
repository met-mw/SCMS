<?php
namespace App\Models;


use App\Modules\Structures\Models\Structure;
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

    public function prepareRelations() {
        $this->field('structure_id')->addRelationMTO(DataSource::factory(Structure::cls()));
    }

}