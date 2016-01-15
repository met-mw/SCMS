<?php
namespace App\Modules\Employees\Views\Admin\Forms;


use SFramework\Classes\View;

class ViewAuthorization extends View {

    public function currentRender() {
        ?>
        <div class="jumbotron">
            <div class="container text-center">
                <form class="form-inline" id="employee-authorization-form" method="post" action="/admin/modules/employees/authorization/authorize">
                    <div class="form-group">
                        <label class="sr-only" for="employee-authorization-form-email">Email</label>
                        <input type="email" class="form-control" id="employee-authorization-form-email" name="employee-authorization-form-email" placeholder="Email" required="required">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="employee-authorization-form-password">Пароль</label>
                        <input type="password" class="form-control" id="employee-authorization-form-password" name="employee-authorization-form-password" placeholder="Пароль" required="required">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="employee-authorization-form-remember-me" name="employee-authorization-form-remember-me">&nbsp;Запомнить меня
                        </label>
                    </div>
                    &nbsp;
                    <button type="submit" class="btn btn-success" id="employee-authorization-form-sign-in" name="employee-authorization-form-sign-in">Войти</button>
                </form>
            </div>
        </div>
        <?
    }

}