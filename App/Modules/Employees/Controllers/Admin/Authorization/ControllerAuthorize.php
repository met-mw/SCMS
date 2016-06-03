<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\AdministratorAreaController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;

class ControllerAuthorize extends AdministratorAreaController {

    public function actionIndex() {
        if (!Param::post('employee-authorization-form-sign-in', false)->exists()) {
            SCMSNotificationLog::instance()->pushError('Форма авторизации заполнена неверно');
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
        if ($this->EmployeeAuthentication->signIn($email, $password)) {
            $redirect = '/admin';
        } else {
            SCMSNotificationLog::instance()->pushError('Неверно указан email или пароль.');
        }

        $this->Response->send($redirect);
    }

} 