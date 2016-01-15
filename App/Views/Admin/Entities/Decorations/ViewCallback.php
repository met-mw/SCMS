<?php
namespace App\Views\Admin\Entities\Decorations;


class ViewCallback extends MasterView {

    public $callback;

    public function __construct($field, $callback) {
        parent::__construct($field);
        $this->callback = $callback;
    }

    public function currentRender() {
        call_user_func_array($this->callback, [$this->data->{$this->field}]);
    }

}