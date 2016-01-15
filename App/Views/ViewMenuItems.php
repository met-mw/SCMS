<?php
namespace App\Views;


use SFramework\Classes\View;

class ViewMenuItems extends View {

    public $isRoot = false;
    /** @var string */
    public $pathRoot;
    /** @var ViewMenuItem[] */
    public $items = [];

    /**
     * @param string $pathRoot
     * @param bool $isRoot
     */
    public function __construct($pathRoot, $isRoot = false) {
        $this->optional = ['items', 'isRoot'];
        $this->pathRoot = $pathRoot;
        $this->isRoot = $isRoot;
    }

    /**
     * @param string $path
     * @param string $displayName
     * @param string $target
     * @param ViewMenuItems $itemsList
     *
     * @return $this
     */
    public function addItem($path, $displayName, $target = null, $itemsList = null) {
        $this->items[$path] = new ViewMenuItem($this->pathRoot, $path, $displayName, $target, $itemsList);
        return $this;
    }

    /**
    * @param string $path
    * @return ViewMenuItem
    */
    public function getItem($path) {
        return $this->items[$path];
    }

    public function currentRender() {
        ?>
        <ul class="navbar-left <?= ($this->isRoot ? 'nav navbar-nav multi-level' : 'nav dropdown-menu"') ?>">
            <? foreach ($this->items as $item): ?>
                <? $item->render(); ?>
            <? endforeach; ?>
        </ul>
        <?
    }
}