<?php
namespace App\Views\Admin\Entities\Decorations;


use App\Views\Admin\MainList\Decorations\MasterView as MainListMasterView;
use SORM\Entity;

/**
 * Class ViewParent
 * @package App\Views\Admin\Entities\Decorations
 *
 * @property Entity $data;
 */
class ViewParent extends MainListMasterView {

    public $url;
    public $displayFieldName;

    public function __construct($displayFieldName, $url) {
        $this->displayFieldName = $displayFieldName;
        $this->url = $url;
    }

    public function currentRender() {
        /** @var Entity $data */
        ?>
        <a href="<?= $this->url ?>?parent_pk=<?= ($this->data->getPrimaryKey()) ?>">
            <?= $this->data->{$this->displayFieldName} ?>
        </a>
        <?
    }

}