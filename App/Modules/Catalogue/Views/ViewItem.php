<?php
namespace App\Modules\Catalogue\Views;


use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\View;

class ViewItem extends View {

    /** @var Item */
    public $oItem;
    public $backUrl;

    public function currentRender() {
        ?>
            <?= $this->oItem->description ?>
        <?
    }

}