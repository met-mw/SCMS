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
 * @property int $id;
 * @property string $name;
 * @property string $description;
 * @property int $structure_id;
 * @property string $path;
 * @property string $frame;
 * @property int $module_id;
 * @property int $anchor;
 * @property int $priority;
 * @property int $active;
 */
class Structure extends Entity {

    protected $tableName = 'structure';

    public function prepareRelations() {
        $this->field('structure_id')
            ->addRelationMTO(DataSource::factory(Structure::cls()));

        $this->field('module_id')
            ->addRelationMTO(DataSource::factory(Module::cls()));

        $this->field()
            ->addRelationOTM(DataSource::factory(Structure::cls()), 'structure_id')
            ->addRelationOTM(DataSource::factory(StructureSetting::cls()), 'structure_id');
    }

}