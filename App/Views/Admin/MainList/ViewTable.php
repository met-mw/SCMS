<?php
namespace App\Views\Admin\MainList;


use App\Views\Admin\Entities\ViewTableHead;
use SFramework\Classes\View;

class ViewTable extends View {

    const DECORATION_FIELD = 0;
    const DECORATION_VALUE = 1;
    const DECORATION_EXPRESSION = 2;

    /** @var ViewTableHead */
    public $tableHead;
    /** @var ViewTableBody */
    public $tableBody;

    /** @var string */
    public $caption;
    public $description;

    /**
     * @param string $caption
     *
     * @return $this
     */
    public function setCaption($caption) {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @param string $columnName
     * @param string $columnDisplayName
     *
     * @return $this
     */
    public function addColumn($columnName, $columnDisplayName) {
        $this->tableHead->addColumn($columnName, $columnDisplayName);
        $this->tableBody->addColumn($columnName, $columnDisplayName);
        return $this;
    }

    /**
     * @param string $name
     * @param string $url
     * @param string $icon
     *
     * @param bool $group
     * @param array $classes
     *
     * @return $this
     */
    public function addAction($name, $url, $icon, $group = false, array $classes = []) {
        $this->tableBody->addAction($name, $url, $icon, $group, $classes);
        $this->tableHead->allowActions = true;

        return $this;
    }

    public function currentRender() {
        ?>
        <div class="panel panel-info">
        <div class="panel-heading"><?= $this->caption ?></div>
            <div class="panel-body">
                <p><?= $this->description ?></p>
            </div>
        </div>
        <form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <? $this->tableHead->render(); ?>
                    <? $this->tableBody->render(); ?>
                </table>
            </div>
        </form>
    <?
    }

}