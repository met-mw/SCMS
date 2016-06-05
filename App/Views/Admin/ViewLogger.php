<?php


namespace App\Views\Admin;


use SDataGrid\Classes\DataGrid;
use SFramework\Classes\View;

class ViewLogger extends View
{

    /** @var DataGrid */
    public $DataGrid;

    public function currentRender()
    {
        ?><div class="table-responsive"><?
            $this->DataGrid->render();
        ?></div><?
    }

}