<?php
namespace App\Modules\Pages\Models;


use SORM\Entity;

/**
 * Class Page
 * @package App\Modules\Pages\Models
 *
 * @property int $id;
 * @property string $name;
 * @property string $description;
 * @property string $content;
 * @property int $created;
 */
class Page extends Entity {

    protected $tableName = 'module_pages';

    public function prepareRelations() {

    }

}