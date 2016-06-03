<?php
namespace App\Classes\Authentication;


use App\Modules\Employees\Models\Admin\Employee;
use SignInBase\Classes\AbstractAuthenticate;
use SORM\DataSource;

class HttpAuthentication extends AbstractAuthenticate
{

    /** @var Employee */
    protected $oCurrentEmployee = null;

    public function getCurrentUser()
    {
        return $this->oCurrentEmployee;
    }

    /**
     * Проверка аутернтификации пользователя
     *
     * @return bool
     */
    public function authenticated()
    {
        return !is_null($this->oCurrentEmployee);
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

        $this->oCurrentEmployee = $oEmployee;

        return true;
    }

}