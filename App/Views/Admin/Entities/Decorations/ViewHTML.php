<?php
namespace App\Views\Admin\Entities\Decorations;


use SORM\Entity;

/**
 * Class ViewHTML
 * @package App\Views\Admin\Entities\Decorations
 *
 * @property Entity $data;
 */
class ViewHTML extends MasterView {

    public function currentRender() {
        ?>
        <iframe style="width: 100%; height: 50px;"><?= $this->data->{$this->field} ?></iframe>
        <?
    }

}