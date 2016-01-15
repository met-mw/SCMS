<?php
namespace App\Classes;


use SFramework\Classes\Controller;
use SFramework\Classes\Registry;

class MasterAdminProxyController extends Controller {

    /** @var Proxy */
    protected $proxy;

    public function __construct() {
        $this->proxy = new Proxy(Registry::router());
    }

} 