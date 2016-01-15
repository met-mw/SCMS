<?php
namespace App\Views\Admin\Entities;


use App\Views\Admin\MainList\ViewList as ViewMasterList;
use App\Views\Admin\MainList\ViewMenu;
use App\Views\Admin\MainList\ViewTable;
use App\Views\Admin\ViewConfirmModal;
use App\Views\Admin\ViewNotifications;
use App\Views\Admin\ViewPagination;

class ViewList extends ViewMasterList {

    public function __construct() {
        parent::__construct();

        $this->menu = new ViewMenu();
        $this->table = new ViewTable();
        $this->table->tableHead = new ViewTableHead();
        $this->table->tableBody = new ViewTableBody();
        $this->pagination = new ViewPagination();
        $this->notificationsView = new ViewNotifications();
        $this->confirmModalView = new ViewConfirmModal();
    }

}