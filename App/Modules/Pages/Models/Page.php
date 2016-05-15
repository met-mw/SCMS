<?php
namespace App\Modules\Pages\Models;


use DateTime;
use SORM\Entity;

/**
 * Class Page
 * @package App\Modules\Pages\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $content
 * @property bool $active
 * @property bool $deleted
 */
class Page extends Entity {

    protected $tableName = 'module_pages';

}