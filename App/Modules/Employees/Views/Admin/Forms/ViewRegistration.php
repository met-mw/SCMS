<?php


namespace App\Modules\Employees\Views\Admin\Forms;


use SFramework\Classes\View;

class ViewRegistration extends View {

    public function currentRender() {
        ?>
        <div class="container text-center">
            <form class="form-horizontal" style="max-width: 400px; margin: 0 auto;" id="employee-registration-form" method="post" action="/admin/modules/employees/registration/signup">
                <fieldset class="text-left">
                    <div class="form-group">
                        <label for="employee-registration-form-name">Имя</label>
                        <input type="text" class="form-control" id="employee-registration-form-name" name="employee-registration-form-name" placeholder="Имя нового сотрудника" required="required">
                    </div>
                    <div class="form-group">
                        <label for="employee-registration-form-email">Email</label>
                        <input type="email" class="form-control" id="employee-registration-form-email" name="employee-registration-form-email" placeholder="Email нового сотрудника" required="required">
                    </div>
                    <div class="form-group">
                        <label for="employee-registration-form-password">Пароль</label>
                        <input type="password" class="form-control" id="employee-registration-form-password" name="employee-registration-form-password" placeholder="Пароль нового сотрудника" required="required">
                    </div>
                    <div class="form-group">
                        <label for="employee-registration-form-password-repeat">Повтор пароля</label>
                        <input type="password" class="form-control" id="employee-registration-form-password-repeat" name="employee-registration-form-password-repeat" placeholder="Повтор пароля нового сотрудника" required="required">
                    </div>
                </fieldset>
                <button type="submit" class="btn btn-success" id="employee-registration-form-sign-up" name="employee-registration-form-sign-up">Зарегистрировать сотрудника</button>
            </form>
        </div>
        <?
    }

}