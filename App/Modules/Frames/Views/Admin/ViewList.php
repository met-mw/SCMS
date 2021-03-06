<?php
namespace App\Modules\Frames\Views\Admin;


use App\Views\Admin\MainList\ViewList as ViewMasterList;
use App\Views\Admin\MainList\ViewMenu;
use App\Views\Admin\MainList\ViewTable;

class ViewList extends ViewMasterList {

    public function __construct() {
        parent::__construct();

        $this->menu = new ViewMenu();
        $this->table = new ViewTable();
        $this->table->tableHead = new ViewTableHead();
        $this->table->tableBody = new ViewTableBody();
    }

} 