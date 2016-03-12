<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();
        $employeeId = Param::get('id')
            ->noEmpty('Параметр обязателен для заполнения.')
            ->asInteger(true, "Неверно задан параметр.");
        /** @var Employee $oEmployee */
        $oEmployee = DataSource::factory(Employee::cls(), $employeeId);
        if ($oEmployee->id) {
            NotificationLog::instance()->pushMessage("Сотрудник \"$oEmployee->name\" успешно удалён.");
            $oEmployee->deleted = true;
            $oEmployee->commit();
        } else {
            NotificationLog::instance()->pushError("Сотрутник с ID {$employeeId} не найден");
        }


        $this->response->send();
    }

} 