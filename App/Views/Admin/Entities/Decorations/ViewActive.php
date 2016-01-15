<?php
namespace App\Views\Admin\Entities\Decorations;


use App\Views\Admin\MainList\Decorations\MasterView;
use SORM\Entity;

/**
 * Class ViewActive
 * @package App\Views\Admin\Entities\Decorations
 *
 * @property Entity $data;
 */
class ViewActive extends MasterView {

    public $fieldName;

    public function __construct($fieldName) {
        $this->fieldName = $fieldName;
    }

    public function currentRender() {
        /** @var Entity $data */
        ?>
        <span class="label label-<?= ($this->data->{$this->fieldName} == 1 ? 'success' : 'default') ?>">
            <?= ($this->data->{$this->fieldName} == 1 ? 'Да' : 'Нет') ?>
        </span>
        <?
    }

}