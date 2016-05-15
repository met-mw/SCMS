<?php
namespace App\Modules\Employees\Models\Admin;


use DateTime;
use SORM\Entity;

/**
 * Class Employee
 * @package App\Modules\Employees\Models\Admin
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property bool $deleted
 * @property bool $active
 * @property-read DateTime $created
 * @property-read DateTime $updated
 */
class Employee extends Entity {

    const SALT = 'eRp2x1';

    protected $tableName = 'module_employees';

}