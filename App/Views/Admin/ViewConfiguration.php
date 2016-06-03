<?php
namespace App\Views\Admin;


use App\Classes\Configurator;
use SFramework\Classes\View;

class ViewConfiguration extends View
{

    /** @var Configurator */
    public $Configurator;

    public function currentRender()
    {
        ?>
        Вывод формы конфига
        <?
    }

}