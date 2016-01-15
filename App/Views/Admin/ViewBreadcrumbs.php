<?php
namespace App\Views\Admin;


class ViewBreadcrumbs extends \SFramework\Views\ViewBreadcrumbs {

    public function currentRender() {
        $count = count($this->breadcrumbs);
        ?>
        <ol class="breadcrumb">
            <? for ($i = 0; $i < $count; $i++): $breadcrumb = $this->breadcrumbs[$i]; ?>
                <li<?= ($count-1 == $i ? ' class="active"' : '') ?>>
                    <?= ($i < $count - 1 ? '<a href="' . $breadcrumb->getRealPath('admin') . '">' . $breadcrumb->getName() . '</a>' : $breadcrumb->getName()) ?>
                </li>
            <? endfor; ?>
        </ol>
        <?
    }

}