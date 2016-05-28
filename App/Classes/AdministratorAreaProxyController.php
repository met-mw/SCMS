<?php
namespace App\Classes;


use SFramework\Classes\Controller;
use SFramework\Classes\Registry;

class AdministratorAreaProxyController extends Controller
{

    /** @var Proxy */
    protected $Proxy;

    public function __construct() {
        $this->Proxy = new Proxy(Registry::router());
    }

} 