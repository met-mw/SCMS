<?php
namespace App\Modules\Employees\Controllers\Admin\API;


use App\Classes\AdministratorAreaController;

class ControllerList extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->httpAuthorizeIfNot();

        $oCurrentEmployee = $this->HTTPEmployeeAuthentication->getCurrentUser();
        $employeeDataArray = json_decode($oCurrentEmployee->asJSON(), true);
        echo $this->Response->arrayToJSON(['authenticate' => 'success', 'employee' => $employeeDataArray]);
    }

}