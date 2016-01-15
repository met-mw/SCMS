<?php
namespace App\Views\Admin\MainList\Decorations;


use SFramework\Classes\View;

abstract class MasterView extends View {

    public $data;

    public function bindData($data) {
        $this->data = $data;
    }

} 