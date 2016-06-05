<?php
namespace App\Views\Admin;


use App\Classes\Authentication\Authentication;
use App\Modules\Employees\Models\Admin\Employee;
use App\Views\ViewMenu as ViewMasterMenu;

class ViewMenu extends ViewMasterMenu {

    /** @var Authentication */
    public $Authentication;

    public function __construct($projectName, Authentication $Authentication) {
        $this->pathRoot = 'admin';
        $this->Authentication = $Authentication;
        parent::__construct($projectName);
    }

    public function currentRender() {
        ?>
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" target="_blank" href="/" title="Просмотреть пользовательскую часть сайта"><span class="glyphicon glyphicon-eye-open"></span></a>
                    &nbsp;&nbsp;
                    <a class="navbar-brand" href="/<?= $this->pathRoot ?>"><?= $this->projectName ?></a>
                </div>

                <div class="collapse navbar-collapse" id="bs-navbar-collapse">
                    <? $this->itemsList->render() ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/admin/modules/employees/authorization/unauthorize">Выход</a>
                        </li>
                    </ul>
                    <p class="navbar-text navbar-right" style="color: #ffffff; font-weight: 600;"><span class="glyphicon glyphicon-user"></span>&nbsp;<?= $this->Authentication->getCurrentUser()->name ?></p>
                </div>
            </div>
        </nav>
    <?
    }

}