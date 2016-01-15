<?php
namespace App\Models;


use App\Modules\Structures\Models\Structure;
use SORM\DataSource;
use SORM\Entity;

/**
 * Class Module
 * @package App\Models
 *
 * @property int $id;
 * @property string $name;
 * @property string $alias;
 * @property string $description;
 * @property bool $allow_user_area;
 * @property bool $active;
 */
class Module extends Entity {

    protected $tableName = 'module';

    public function prepareRelations() {
        $this->field()
            ->addRelationOTM(DataSource::factory(Structure::cls()), 'module_id')
            ->addRelationOTM(DataSource::factory(ModuleSetting::cls()), 'module_id');
    }
}