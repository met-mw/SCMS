<?php
namespace App\Views\Admin\Entities\Decorations;


use SORM\Entity;

/**
 * Class ViewActive
 * @package App\Views\Admin\Entities\Decorations
 *
 * @property Entity $data;
 */
class ViewActive extends MasterView {

    public function currentRender() {
        /** @var Entity $data */
        $isActive = $this->data->{$this->field};
        ?>
        <span class="label label-<?= $isActive ? 'success' : 'default' ?>">
            <?= $isActive ? 'Да' : 'Нет' ?>
        </span>
        <?
    }

}