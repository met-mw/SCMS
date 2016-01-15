<?php
namespace App\Modules\Employees\Models\Admin;


use SORM\Entity;

/**
 * Class Employee
 * @package App\Modules\Employees\Models\Admin
 *
 * @property int $id;
 * @property string $email;
 * @property string $name;
 * @property string $password;
 * @property bool $active;
 */
class Employee extends Entity {

    protected $tableName = 'module_employees';

    public function prepareRelations() {

    }

}