<?php
namespace App\Modules\Employees\Controllers\Admin\Api;


use App\Classes\AdministratorAreaController;

class ControllerList extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->needHttpAuthenticate();

        $oCurrentEmployee = $this->HTTPEmployeeAuthentication->getCurrentUser();
        $employeeDataArray = json_decode($oCurrentEmployee->asJSON(), true);
        echo $this->Response->arrayToJSON(['authenticate' => 'success', 'message' => 'Авторизация прошла успешно', 'employee' => $employeeDataArray]);
    }

}