<?php


namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $employeeId = Param::post('employee-id')->asInteger(true, 'Не указан обязательный параметр.');

        $name = Param::post('employee-name')
            ->noEmpty('Поле "Имя" должно быть заполнено.')
            ->asString();
        $email = Param::post('employee-email')
            ->noEmpty('Поле "Email" должно быть заполнено.')
            ->asString();
        $currentEmployeePassword = Param::post('employee-current-password')
            ->asString();
        $newPassword = Param::post('employee-new-password')
            ->asString();
        $newPasswordRepeat = Param::post('employee-new-password-repeat')
            ->asString();

        if (!empty($newPassword)) {
            if (!$this->employeeAuthorizator->verifyPassword($this->employeeAuthorizator->getCurrentUser(), $currentEmployeePassword)) {
                NotificationLog::instance()->pushError('Вы указали неверный пароль.');
            }

            if ($newPassword != $newPasswordRepeat) {
                NotificationLog::instance()->pushError('"Новый пароль" и "Повтор нового пароля" должны быть заполены одинаково.');
            }
        }

        /** @var Employee $oEmployee */
        $oEmployee = DataSource::factory(Employee::cls(), $employeeId);
        if (!$oEmployee->getPrimaryKey()) {
            NotificationLog::instance()->pushError('Редактируемый сотрудник не определён.');
        }

        /** @var Employee $aEmployee */
        $aEmployee = DataSource::factory(Employee::cls());
        $aEmployee->builder()
            ->where("{$aEmployee->getPrimaryKeyName()}<>{$employeeId}")
            ->whereAnd()
            ->where('deleted=0')
            ->whereAnd()
            ->where('active=1')
            ->whereAnd()
            ->where("email='{$email}'")
            ->limit(1);
        $aEmployees = $aEmployee->findAll();
        if (sizeof($aEmployees) > 0) {
            NotificationLog::instance()->pushError('Данный Email уже используется другим сотрудником.');
        }

        if (!NotificationLog::instance()->hasProblems()) {
            $oEmployee->name = $name;
            $oEmployee->email = $email;
            $oEmployee->password = $this->employeeAuthorizator->preparePassword($newPassword);

            $oEmployee->commit();

            NotificationLog::instance()->pushMessage("Сотрудник \"{$oEmployee->email}\" успешно отредактирован");
            $redirect = '';
            if (Param::post('employee-accept', false)->exists()) {
                $redirect = '/admin/modules/employees/';
            } else {
                if ($employeeId == 1) {
                    $redirect = "/admin/modules/employees/edit/?pk={$oEmployee->getPrimaryKey()}";
                }
            }
            $this->response->send($redirect);
        } else {
            $this->response->send();
        }
    }

} 