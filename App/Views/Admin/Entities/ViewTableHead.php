<?php
namespace App\Views\Admin\Entities;



use App\Views\Admin\MainList\ViewTableHead as ViewMasterTableHead;

class ViewTableHead extends ViewMasterTableHead {

    public function currentRender() {
        ?>
        <thead>
        <tr>
            <th><span class="glyphicon glyphicon-check"><span></th>
            <? foreach (array_values($this->columns) as $column): ?>
                <th><?= $column ?></th>
            <? endforeach; ?>
            <? if ($this->allowActions): ?>
            <th style="width: 80px;"><span class="glyphicon glyphicon-th-list"><span></th>
            <? endif; ?>
        </tr>
        </thead>
        <?
    }

}