<?php
namespace App\Views\Admin\DataGrid\Menu;


use SFramework\Classes\DataGrid\Menu\Item;
use SFramework\Classes\View;

class ViewItem extends View {

    /** @var Item */
    public $item;

    public function __construct(Item $item) {
        $this->item = $item;
    }

    public function currentRender() {
        ?>
        <a class="btn btn-sm btn-success" href="<?= $this->item->getUrl() ?>"<?= $this->item->hasAttributes() ? ' ' . $this->item->buildAttributes() : '' ?>><?= $this->item->getDisplayName() ?></a>
        <?
    }

} 