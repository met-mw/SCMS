<?php
namespace App\Views\Admin;


use App\Modules\Employees\Models\Admin\Employee;
use App\Views\ViewMenu as ViewMasterMenu;

class ViewMenu extends ViewMasterMenu {

    /** @var Employee */
    public $currentEmployee;

    public function __construct($projectName, Employee $currentEmployee = null) {
        $this->optional[] = 'currentEmployee';

        $this->pathRoot = 'admin';
        $this->currentEmployee = $currentEmployee;
        parent::__construct($projectName);
    }

    public function currentRender() {
        ?>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/<?= $this->pathRoot ?>"><?= $this->projectName ?></a>
                </div>

                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <? $this->itemsList->render() ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/admin/modules/employees/authorization/unauthorize">Выход</a>
                        </li>
                    </ul>
                    <p class="navbar-text navbar-right"><span class="label label-success">Активный сотрудник: <?= $this->currentEmployee->name ?></span></p>
                </div>
            </div>
        </nav>
    <?
    }

}