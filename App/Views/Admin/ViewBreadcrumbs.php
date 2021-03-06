<?php
namespace App\Views\Admin;



class ViewBreadcrumbs extends \SFramework\Views\ViewBreadcrumbs {

    public function currentRender() {
        $count = count($this->Breadcrumbs);
        $currentRoot = '';
        if ($count > 0): ?>
        <ol class="breadcrumb">
            <? for ($i = 0; $i < $count; $i++): $breadcrumb = $this->Breadcrumbs[$i]; ?>
                <li<?= ($count-1 == $i ? ' class="active"' : '') ?>>
                    <?= ($i < $count - 1 ? '<a href="' . $currentRoot . $breadcrumb->getPath() . '">' . $breadcrumb->getName() . '</a>' : $breadcrumb->getName()) ?>
                </li>
                <? if (!$breadcrumb->isParam()) { $currentRoot .= $breadcrumb->getPath(); } ?>
            <? endfor; ?>
        </ol>
        <? endif;
    }

}