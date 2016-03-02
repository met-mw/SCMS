<?php


namespace App\Modules\Employees\Views\Admin;


use App\Modules\Employees\Models\Admin\Employee;
use App\Views\Admin\ViewNotifications;
use SFramework\Classes\View;

class ViewEmployeeEdit extends View {

    /** @var Employee */
    public $employee;
    /** @var ViewNotifications */
    public $notificationsView;

    public function __construct() {
        $this->notificationsView = new ViewNotifications();
    }

    public function currentRender() {
        ?>
        <form action="/admin/modules/employees/save/" method="post" id="employee-edit-form">
            <fieldset>
                <legend>Редактирование сотрудника</legend>
                <input type="hidden" id="employee-id" name="employee-id" value="<?= $this->employee->getPrimaryKey() ?>" />

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="employee-number">№</label>
                                    <input class="form-control input-sm" name="employee-number" id="employee-number" disabled="disabled" type="number" placeholder="№" value="<?= $this->employee->getPrimaryKey() ?>">
                                    <span class="help-block">Номер</span>
                                </div>
                            </div>
                            <div class="col-lg-11">
                                <div class="form-group">
                                    <label for="employee-name">Имя</label>
                                    <input class="form-control input-sm" name="employee-name" id="employee-name" type="text" placeholder="Имя" value="<?= $this->employee->name ?>">
                                    <span class="help-block">ФИО сотрудника</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="employee-email">Email</label>
                                    <input class="form-control input-sm" name="employee-email" id="employee-email" type="email" placeholder="Email" value="<?= $this->employee->email ?>">
                                    <span class="help-block">Адрес электронной почты, используется при авторизации сотрудника в панели управления.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="employee-current-password">Пароль текущего пользователя</label>
                                    <input class="form-control input-sm" name="employee-current-password" id="employee-current-password" type="password" placeholder="Пароль текущего пользователя">
                                    <span class="help-block">Для изменения пароля сотрудника нужно указать пароль акстивного пользователя.</span>
                                </div>
                                <div class="form-group">
                                    <label for="employee-new-password">Новый пароль</label>
                                    <input class="form-control input-sm" name="employee-new-password" id="employee-new-password" type="password" placeholder="Новый пароль">
                                    <span class="help-block">Новый пароль сотрудника.</span>
                                </div>
                                <div class="form-group">
                                    <label for="employee-new-password-repeat">Повтор нового пароля</label>
                                    <input class="form-control input-sm" name="employee-new-password-repeat" id="employee-new-password-repeat" type="password" placeholder="Повтор нового пароля">
                                    <span class="help-block">Повтор нового пароля сотрудника.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <button name="structure-save" id="structure-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button name="structure-accept" id="structure-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
        $this->notificationsView->render();
    }

}