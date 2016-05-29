<?php
namespace App\Classes;


use SFramework\Classes\Controller;
use SFramework\Classes\Registry;

class AdministratorAreaProxyController extends Controller
{

    /** @var RouterProxy */
    protected $Proxy;

    public function __construct() {
        $this->Proxy = new RouterProxy(Registry::router());
    }

} 