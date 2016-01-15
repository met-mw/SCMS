<?php
namespace App\Views\Admin\Entities\Decorations;


class ViewDisplayData extends MasterView {

    /** @var string */
    public $displayData;

    public function __construct($field, $displayData) {
        parent::__construct($field);
        $this->displayData = $displayData;
    }

    public function currentRender() {
        echo $this->displayData;
    }

}