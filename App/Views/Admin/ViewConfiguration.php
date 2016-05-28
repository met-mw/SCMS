<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewConfiguration extends View
{

    public function currentRender()
    {
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    Данный раздед панели администратора находится в разработке.
                </div>
            </div>
        </div>
        <?
    }

}