<?php
namespace App\Modules\Employees\Classes;


use App\Classes\Authentication\Authentication;
use App\Modules\Employees\Models\Admin\Employee;
use SORM\DataSource;
use SORM\Tools\Builder;

class Authorizator {

    /** @var Authentication */
    public $EmployeeAuthentication;

    /** @var Employee */
    protected $currentUser = null;

    public function __construct() {
        $this->EmployeeAuthentication = new Authentication();
    }

    public function authenticated() {
        return $this->EmployeeAuthentication->authenticated();
    }

} 