<?php
namespace App\Views\Admin\Entities\Decorations;


use App\Views\Admin\MainList\Decorations\MasterView as MainListMasterView;

abstract class MasterView extends MainListMasterView {

    public $field;

    public function __construct($field) {
        $this->field = $field;
    }

}