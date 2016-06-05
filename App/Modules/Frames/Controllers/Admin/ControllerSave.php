<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use Exception;
use SFileSystem\Classes\File;
use SFramework\Classes\CoreFunctions;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;

class ControllerSave extends AdministratorAreaController {

    public function actionIndex() {
        if (CoreFunctions::isAJAX() && !$this->EmployeeAuthentication->authenticated()) {
            SCMSNotificationLog::instance()->pushError('Нет доступа!');
            $this->Response->send();
            return;
        }

        $this->needAuthenticate();

        $frameName = Param::post('frame-name')->asString();
        $frameContent = Param::post('frame-content')->asString();

        $FrameFile = new File(SFW_MODULES_FRAMES . $frameName);
        $isNew = !$FrameFile->exists();
        $FrameFile->setContent($frameContent);

        if (Param::post('frame-accept', false)->exists()) {
            $redirect = '/admin/modules/frames/';
        } else {
            $redirect = $isNew ? "/admin/modules/frames/edit/?name={$frameName}" : '';
        }

        SCMSNotificationLog::instance()->pushMessage("Фрейм \"{$frameName}\" успешно " . ($isNew ? 'создан' : 'отредактирован') . '!');
        $this->Response->send($redirect);
    }

} 