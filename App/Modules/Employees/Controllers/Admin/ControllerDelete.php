<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();
        $employeeId = Param::get('id')
            ->noEmpty('Параметр обязателен для заполнения.')
            ->asInteger(true, "Неверно задан параметр.");
        /** @var Employee $oEmployee */
        $oEmployee = DataSource::factory(Employee::cls(), $employeeId);
        if ($oEmployee->id) {
            SCMSNotificationLog::instance()->pushMessage("Сотрудник \"$oEmployee->name\" успешно удалён.");
            $oEmployee->deleted = true;
            $oEmployee->commit();
        } else {
            SCMSNotificationLog::instance()->pushError("Сотрутник с ID {$employeeId} не найден");
        }


        $this->Response->send();
    }

} 