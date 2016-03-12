<?php
namespace App\Views\Admin\MainList;


use App\Views\Admin\ViewResponse;
use SFramework\Classes\View;
use SFramework\Views\ViewPagination;

class ViewList extends View {

    /** @var ViewMenu */
    public $menu;
    /** @var ViewTable */
    public $table;
    /** @var ViewResponse */
    public $response;
    /** @var ViewPagination */
    public $pagination;

    public function __construct() {
        $this->optional[] = 'response';
    }

    public function currentRender() {
        if ($this->response) {
            $this->response->render();
        }
        $this->menu->render();
        if ($this->pagination->pagesCount) {
            $this->pagination->render();
        }
        $this->table->render();
        if ($this->pagination->pagesCount) {
            $this->pagination->render();
        }
    }

}