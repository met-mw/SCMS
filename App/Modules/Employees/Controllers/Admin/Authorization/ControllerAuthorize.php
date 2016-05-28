<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\AdministratorAreaController;
use App\Modules\Employees\Models\Admin\Employee;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;

class ControllerAuthorize extends AdministratorAreaController {

    public function actionIndex() {
        if (!Param::post('employee-authorization-form-sign-in', false)->exists()) {
            NotificationLog::instance()->pushError('Форма авторизации заполнена неверно');
            $this->Response->send();
            exit;
        }

        $email = Param::post('employee-authorization-form-email')
            ->noEmpty('Заполните Email.')
            ->asEmail(true, 'Недопустимый Email.');
        $password = Param::post('employee-authorization-form-password')
            ->noEmpty('Заполните пароль.')
            ->asString(true, 'Недопустимый пароль.');

        $redirect = '';
        if ($this->EmployeeAuthorizator->authorize($email, $password)) {
            $redirect = '/admin';
        } else {
            NotificationLog::instance()->pushError('Неверно указан email или пароль.');
        }

        $this->Response->send($redirect);
    }

} 