<?php
namespace App\Modules\Employees\Classes;


use App\Modules\Employees\Models\Admin\Employee;
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

    public function preparePassword($password) {
        return password_hash($password . Employee::SALT, PASSWORD_DEFAULT);
    }

    public function verifyPassword(Employee $oEmployee, $password) {
        return password_verify($password . Employee::SALT, $oEmployee->password);
    }

    public function authorize($email, $password) {
        /** @var Employee $oEmployees */
        $oEmployees = DataSource::factory(Employee::cls());
        $oEmployees->builder()
            ->where("email='{$email}'")
            ->whereAnd()
            ->where('active=1');
        /** @var Employee[] $aEmployees */
        $aEmployees = $oEmployees->findAll();
        if (empty($aEmployees)) {
            return false;
        }

        $oEmployee = $aEmployees[0];
        if (!$this->verifyPassword($oEmployee, $password)) {
            return false;
        }

        $_SESSION['employee'] = $oEmployee->id;
        $this->currentUser = $oEmployee;

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