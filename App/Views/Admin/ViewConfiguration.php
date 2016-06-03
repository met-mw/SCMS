<?php
namespace App\Views\Admin;


use SDataGrid\Classes\DataGrid;
use SFramework\Classes\View;

class ViewConfiguration extends View
{

    /** @var DataGrid */
    public $DataGrid;

    public function currentRender()
    {
        ?><form enctype="multipart/form-data"><?
            ?><fieldset><?
                ?><div class="table-responsive"><?
                    $this->DataGrid->render();
                ?></div><?
            ?></fieldset><?
            ?><hr/><?
            ?><div class="text-right"><?
                ?><input type="submit" class="btn btn-success" value="Сохранить параметры" /><?
            ?></div><?
        ?></form><?
    }

}