<?php
namespace App\Views\Admin\MainList;


use SFramework\Classes\View;

abstract class ViewTableHead extends View {

    /** @var boolean */
    public $allowActions = false;
    /** @var string[] */
    public $columns;

    public function __construct() {
        $this->optional = ['allowActions'];
    }

    public function addColumn($columnName, $columnDisplayName) {
        $this->columns[$columnName] = $columnDisplayName;
        return $this;
    }

}