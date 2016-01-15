<?php
namespace App\Modules\Frames\Views\Admin;


use App\Views\Admin\MainList\ViewTableHead as ViewMasterTableHead;

class ViewTableHead extends ViewMasterTableHead {

    public function currentRender() {
        ?>
        <thead>
        <tr>
            <? foreach (array_values($this->columns) as $column): ?>
                <th><?= $column ?></th>
            <? endforeach; ?>
            <? if ($this->allowActions): ?>
                <th style="width: 80px;">Действия</th>
            <? endif; ?>
        </tr>
        </thead>
    <?
    }

}