<?php
namespace App\Views\Admin;


use SFramework\Classes\Breadcrumb;

class ViewBreadcrumbs extends \SFramework\Views\ViewBreadcrumbs {

    public function currentRender() {
        $count = count($this->breadcrumbs);
        $currentRoot = '';
        ?>
        <ol class="breadcrumb">
            <? for ($i = 0; $i < $count; $i++): $breadcrumb = $this->breadcrumbs[$i]; ?>
                <li<?= ($count-1 == $i ? ' class="active"' : '') ?>>
                    <?= ($i < $count - 1 ? '<a href="' . $currentRoot . $breadcrumb->getPath() . '">' . $breadcrumb->getName() . '</a>' : $breadcrumb->getName()) ?>
                </li>
                <? if (!$breadcrumb->isParam()) { $currentRoot .= $breadcrumb->getPath(); } ?>
            <? endfor; ?>
        </ol>
        <?
    }

}