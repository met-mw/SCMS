<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;

class ControllerDelete extends MasterAdminController {

    public function actionIndex() {
        if (CoreFunctions::isAJAX()) {
            if ($this->employeeAuthorizator->authorized()) {
                NotificationLog::instance()->pushError('Нет доступа.');
            }

            $this->response->send();
            return;
        }

        $this->authorizeIfNot();
    }

    public function actionGroup() {
//        NotificationLog::instance()->pushError('Ошибка!');
        NotificationLog::instance()->pushMessage('Сообщение');
        $this->response->send();
    }

} 