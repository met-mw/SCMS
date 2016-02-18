<?php
namespace App\Views\Admin\Entities\Decorations;


use App\Views\Admin\MainList\Decorations\MasterView;
use SORM\Entity;

class ViewMailTo extends MasterView {

    public $displayFieldName;

    public function __construct($displayFieldName) {
        $this->displayFieldName = $displayFieldName;
    }

    public function currentRender() {
        /** @var Entity $data */
        ?>
        <a href="mailto:<?= $this->displayFieldName ?>" title="Написать письмо">
            <?= $this->data->{$this->displayFieldName} ?>
        </a>
    <?
    }

}