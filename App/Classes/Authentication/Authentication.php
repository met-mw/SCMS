<?php
namespace App\Classes\Authentication;


use App\Modules\Employees\Models\Admin\Employee;
use SORM\DataSource;

class Authentication extends \SignInBase\Classes\Session\Authentication
{

    /** @var Employee */
    protected $oCurrentEmployee = null;
    protected $sessionKeyName = 'current_employee';

    public function __construct()
    {
        if (!$this->authenticated() && parent::authenticated()) {
            $this->oCurrentEmployee = DataSource::factory(Employee::cls(), $this->getSessionKey());
        }
    }

    public function authenticated()
    {
        return parent::authenticated() && $this->hasCurrentUser();
    }

    public function hasCurrentUser() {
        return !is_null($this->oCurrentEmployee);
    }

    public function getCurrentUser() {
        return $this->oCurrentEmployee;
    }

    /**
     * Аутентификация
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function signIn($login, $password)
    {
        /** @var Employee $oEmployees */
        $oEmployees = DataSource::factory(Employee::cls());
        $oEmployees->builder()
            ->where("email='{$login}'")
            ->whereAnd()
            ->where('active=1');
        /** @var Employee[] $aEmployees */
        $aEmployees = $oEmployees->findAll();
        if (empty($aEmployees)) {
            return false;
        }

        $oEmployee = $aEmployees[0];
        if (!$this->verifyPassword($password . Employee::SALT, $oEmployee->password)) {
            return false;
        }

        $this->setSessionKey($oEmployee->getPrimaryKey());
        $this->oCurrentEmployee = $oEmployee;

        return true;
    }

    public function signOut()
    {
        $this->oCurrentEmployee = null;
        parent::signOut();
    }

}