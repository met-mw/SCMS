<?php
namespace App\Views\Admin\Entities\Decorations;


use App\Views\Admin\MainList\Decorations\MasterView;
use SORM\Entity;

class ViewLink extends MasterView {

    public $url;
    public $displayFieldName;
    public $params;

    public function __construct($displayFieldName, $url, $params = []) {
        $this->url = $url;
        $this->displayFieldName = $displayFieldName;
        $this->params = $params;
    }

    public function currentRender() {
        $params = '';
        foreach ($this->params as $paramName => $paramFieldName) {
            if (!empty($params)) {
                $params .= '&';
            }

            $params .= "{$paramName}={$this->data->{$paramFieldName}}";
        }

        /** @var Entity $data */
        ?>
        <a href="<?= $this->url ?>?<?= $params ?>">
            <?= $this->data->{$this->displayFieldName} ?>
        </a>
    <?
    }

}