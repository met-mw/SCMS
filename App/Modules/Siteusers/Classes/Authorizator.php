<?php
namespace App\Modules\Siteusers\Classes;


use App\Modules\Siteusers\Models\Siteuser;
use SORM\DataSource;
use SORM\Tools\Builder;

class Authorizator {

    /** @var Siteuser */
    protected $currentUser = null;

    public function __construct() {
        session_start();
    }

    public function authorized() {
        return isset($_SESSION['siteuser']);
    }

    public function defaultPassword() {
        return password_hash(Siteuser::DEFAULT_PASSWORD . Siteuser::SALT, PASSWORD_DEFAULT);
    }

    public function preparePassword($password) {
        return password_hash($password . Siteuser::SALT, PASSWORD_DEFAULT);
    }

    public function verifyPassword(Siteuser $oEmployee, $password) {
        return password_verify($password . Siteuser::SALT, $oEmployee->password);
    }

    public function authorize($email, $password) {
        /** @var Siteuser $oSiteuser */
        $oSiteuser = DataSource::factory(Siteuser::cls());
        $oSiteuser->builder()
            ->where("email='{$email}'")
            ->whereAnd()
            ->where('active=1');
        /** @var Siteuser[] $aSiteusers */
        $aSiteusers = $oSiteuser->findAll();
        if (empty($aSiteusers)) {
            return false;
        }

        $oSiteuser = $aSiteusers[0];
        if (!$this->verifyPassword($oSiteuser, $password)) {
            return false;
        }

        $_SESSION['siteuser'] = $oSiteuser->getPrimaryKey();
        $this->currentUser = $oSiteuser;

        return true;
    }

    public function unauthorize() {
        unset($_SESSION['siteuser']);
        $this->currentUser = null;
    }

    public function getCurrentUser() {
        if (isset($_SESSION['siteuser']) && is_null($this->currentUser)) {
            $this->currentUser = DataSource::factory(Siteuser::cls(), $_SESSION['siteuser']);
        }

        return $this->currentUser;
    }

} 