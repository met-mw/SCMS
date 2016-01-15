<?php
/**
 * Created by PhpStorm.
 * User: metr
 * Date: 22.11.15
 */

namespace App\Views\Admin\Entities\Decorations;


use App\Views\Admin\MainList\Decorations\MasterView as MasterDefaultView;

abstract class MasterView extends MasterDefaultView {

    public $field;

    public function __construct($field) {
        $this->field = $field;
    }

}