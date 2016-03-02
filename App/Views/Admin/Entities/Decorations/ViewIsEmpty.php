<?php


namespace App\Views\Admin\Entities\Decorations;


use SORM\Entity;

class ViewIsEmpty extends MasterView {

    public function currentRender() {
        /** @var Entity $data */
        $value = $this->data->{$this->field};
        $isEmpty = empty($value);
        if ($isEmpty): ?>
            <span class="glyphicon glyphicon-warning-sign text-warning"></span>
        <? else: ?>
            <span class="glyphicon glyphicon-ok success text-success"></span>
        <? endif;
    }

}