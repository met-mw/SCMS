<?php
namespace App\Views;


use SFramework\Classes\View;

class ViewMenuItem extends View {

    /** @var string */
    public $pathRoot;
    /** @var string */
    public $path;
    /** @var string */
    public $displayName;
    /** @var ViewMenuItems */
    public $itemsList;
    /** @var bool */
    public $active = false;
    public $target;

    /**
     * @param string $pathRoot
     * @param string $path
     * @param string $displayName
     * @param string $target
     * @param ViewMenuItems $itemsList
     */
    public function __construct($pathRoot, $path, $displayName, $target = null, $itemsList = null) {
        $this->optional = ['items', 'active', 'target'];

        $this->pathRoot = $pathRoot;
        $this->path = $path;
        $this->displayName = $displayName;
        $this->target = $target;

        $pathRoot = $this->pathRoot == '' ? $this->path : "{$this->pathRoot}/{$this->path}";
        $this->itemsList = is_null($itemsList) ? new ViewMenuItems($pathRoot) : $itemsList;
    }

    /**
     * @param ViewMenuItems $itemList
     *
     * @return $this
     */
    public function addItemsList(ViewMenuItems $itemList) {
        $this->itemsList = $itemList;
        return $this;
    }

    public function currentRender() {
        $classes = [];
        if ($this->active) {
            $classes[] = 'active';
        }
        if (count($this->itemsList->items) > 0) {
            $classes[] = 'dropdown';
        }
        $href = '#';
        if (!in_array('dropdown', $classes)) {
            $href = ($this->pathRoot == '' ? '/' : "/{$this->pathRoot}/") . ($this->path == '' ? '' : "{$this->path}/");
        }

        ?>
        <li <?= (empty($classes) ? '' : 'class="' . implode(' ', $classes) . '"') ?><?= (in_array('dropdown', $classes) ? ' role="presentation"' : '') ?>>
            <a <?= (is_null($this->target) ? '' : "target=\"{$this->target}\"") ?> href="<?= $href ?>"<?= (in_array('dropdown', $classes) ? ' class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"' : '') ?>>
                <?= $this->displayName ?>
                <? if (count($this->itemsList->items) > 0): ?>
                    <span class="caret"></span>
                <? endif; ?>
            </a>
            <? $this->itemsList->render(); ?>
        </li>
        <?
    }

}