<?php
namespace App\Modules\Employees\Classes;


use App\Modules\Employees\Models\Admin\Employee;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Registry;
use SORM\DataSource;
use SORM\Tools\Builder;

class Authorizator {

    /** @var Employee */
    protected $currentUser = null;

    public function __construct() {
        session_start();
    }

    public function authorized() {
        return isset($_SESSION['employee']);
    }

    public function authorize($email, $password) {
        $employees = DataSource::factory(Employee::cls());
        $employees->builder()
            ->where('active=1')
            ->whereAnd()
            ->where("email='{$email}'")
            ->whereAnd()
            ->where("password='{$password}'");
        $employees = $employees->findAll();
        if (empty($employees)) {
            return false;
        }
        /** @var Employee $employee */
        $employee = reset($employees);

        $_SESSION['employee'] = $employee->id;
        $this->currentUser = $employee;

        return true;
    }

    public function unauthorize() {
        unset($_SESSION['employee']);
        $this->currentUser = null;
    }

    public function getCurrentUser() {
        if (isset($_SESSION['employee']) && is_null($this->currentUser)) {
            $this->currentUser = DataSource::factory(Employee::cls(), $_SESSION['employee']);
        }

        return $this->currentUser;
    }

} 