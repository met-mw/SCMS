<?php
namespace App\Views\Admin\MainList;


use SFramework\Classes\View;

class ViewMenu extends View {

    /** @var string[] */
    public $items;

    public function addItem($displayName, $url, $glyphicon = null) {
        $this->items[] = [
            'displayName' => $displayName,
            'url' => $url,
            'glyphicon' => $glyphicon
        ];
        return $this;
    }

    public function currentRender() {
        ?>
        <div class="btn-group" role="group" aria-label="Меню">
            <? foreach ($this->items as $item): ?>
                <a href="<?= $item['url'] ?>" class="btn btn-success">
                    <? if (!is_null($item['glyphicon'])): ?>
                        <span class="glyphicon <?= $item['glyphicon'] ?>" aria-hidden="true"></span>&nbsp;
                    <? endif; ?>
                    <?= $item['displayName'] ?>
                </a>
            <? endforeach; ?>
        </div>
        <hr/>
        <?
    }

}