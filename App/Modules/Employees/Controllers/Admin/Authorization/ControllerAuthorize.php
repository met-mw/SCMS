<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\MasterAdminController;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;

class ControllerAuthorize extends MasterAdminController {

    public function actionIndex() {
        if (!Param::post('employee-authorization-form-sign-in', false)->exists()) {
            NotificationLog::instance()->pushError('Форма авторизации заполнена неверно');
            $this->response->send();
            exit;
        }

        $email = Param::post('employee-authorization-form-email')->asString();
        $password = Param::post('employee-authorization-form-password')->asString();

        $redirect = '';
        if ($this->employeeAuthorizator->authorize($email, $password)) {
            $redirect = '/admin';
        } else {
            NotificationLog::instance()->pushError('Неверно указан email или пароль.');
        }

        $this->response->send($redirect);
    }

} 