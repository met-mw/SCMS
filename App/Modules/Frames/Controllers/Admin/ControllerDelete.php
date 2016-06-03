<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Models\Structure;
use Exception;
use SFileSystem\Classes\File;
use SFramework\Classes\CoreFunctions;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController
{

    public function actionIndex()
    {
        if (CoreFunctions::isAJAX() && !$this->EmployeeAuthentication->authenticated()) {
            SCMSNotificationLog::instance()->pushError('Нет доступа!');
            $this->Response->send();
            return;
        }
        $this->authorizeIfNot();

        $frameName = Param::get('name', true)->asString(true, 'Недопустимое имя фрейма!');

        $FrameFile = new File(SFW_MODULES_FRAMES . $frameName);
        if (!$FrameFile->exists()) {
            SCMSNotificationLog::instance()->pushError("Фрейм с именем \"{$frameName}\" не найден!");
        }

        if (SCMSNotificationLog::instance()->hasProblems()) {
            $this->Response->send();
            return;
        }

        $oStructures = DataSource::factory(Structure::cls());
        $oStructures->builder()
            ->where('deleted=0')
            ->whereAnd()
            ->where("frame='{$frameName}'");
        /** @var Structure[] $aStructures */
        $aStructures = $oStructures->findAll();
        if (sizeof($aStructures) > 0) {
            $structureNames = [];
            foreach ($aStructures as $oStructure) {
                $structureNames[] = $oStructure->name;
            }
            SCMSNotificationLog::instance()->pushError("Фрейм \"{$frameName}\" нельзя удалять, пока он используется в структуре сайта. На данный момент фрейм назначен разделам: \"" . implode('", "', $structureNames) . '"');
        }

        if (SCMSNotificationLog::instance()->hasProblems()) {
            $this->Response->send();
            return;
        }

        try {
            $FrameFile->delete();
        } catch (Exception $e) {
            SCMSNotificationLog::instance()->pushError('При удалении фрейма произошла ошибка.');
        }

        if (!SCMSNotificationLog::instance()->hasProblems()) {
            SCMSNotificationLog::instance()->pushMessage("Фрейм \"{$frameName}\" успешно удалён.");
        }

        $this->Response->send();
    }

}