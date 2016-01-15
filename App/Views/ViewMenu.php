<?php
namespace App\Views;


use SFramework\Classes\View;

abstract class ViewMenu extends View {

    /** @var ViewMenuItems */
    public $itemsList;
    /** @var string */
    public $currentPath;
    /** @var string */
    public $projectName;
    /** @var string */
    public $pathRoot = '';

    /**
     * @param string $projectName
     */
    public function __construct($projectName) {
        $this->optional[] = 'pathRoot';
        $this->projectName = $projectName;
        $this->itemsList = new ViewMenuItems($this->pathRoot, true);
    }

    abstract public function currentRender();

}