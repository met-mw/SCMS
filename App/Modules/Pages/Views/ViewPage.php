<?php
namespace App\Modules\Pages\Views;


use App\Modules\Pages\Models\Page;
use SFramework\Classes\View;

class ViewPage extends View {

    /** @var Page */
    public $page;

    public function currentRender() {
        ?>
        <?= $this->page->content ?>
        <?
    }
}