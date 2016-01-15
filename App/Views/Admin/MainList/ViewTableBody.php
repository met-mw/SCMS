<?php
namespace App\Views\Admin\MainList;


use App\Views\Admin\MainList\Decorations\MasterView;
use SFramework\Classes\View;

abstract class ViewTableBody extends View {

    /** @var string[] */
    public $columns;
    /** @var mixed */
    public $data;
    /** @var MasterView[] */
    protected $fieldDecorations;
    /** @var string[] */
    public $actions;

    public function __construct() {
        $this->optional = [
            'fieldDecorations',
            'actions'
        ];
    }

    public function addColumn($columnName, $columnDisplayName) {
        $this->columns[$columnName] = $columnDisplayName;
        return $this;
    }

    public function addDecoration($fieldName, MasterView $view) {
        $this->fieldDecorations[$fieldName] = $view;
        return $this;
    }

    public function addAction($name, $url, $icon, $group = false, array $classes = []) {
        $this->actions[] = [
            'name' => $name,
            'url' => $url,
            'icon' => $icon,
            'group' => $group,
            'classes' => $classes
        ];

        return $this;
    }

}