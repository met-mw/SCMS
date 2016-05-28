<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use Exception;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;

class ControllerSave extends AdministratorAreaController {

    public function actionIndex() {
        if (CoreFunctions::isAJAX() && !$this->EmployeeAuthorizator->authorized()) {
            NotificationLog::instance()->pushError('Нет доступа!');
            $this->Response->send();
            return;
        }

        $this->authorizeIfNot();

        $frameName = Param::post('frame-name')->asString();
        $frameContent = Param::post('frame-content')->asString();

        $frameFileName = SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR . $frameName;
        $isNew = !file_exists($frameFileName);
        file_put_contents($frameFileName, $frameContent);

        if (Param::post('frame-accept', false)->exists()) {
            $redirect = '/admin/modules/frames/';
        } else {
            $redirect = $isNew ? "/admin/modules/frames/edit/?name={$frameName}" : '';
        }

        NotificationLog::instance()->pushMessage("Фрейм \"{$frameName}\" успешно " . ($isNew ? 'создан' : 'отредактирован') . '!');
        $this->Response->send($redirect);
    }

} 