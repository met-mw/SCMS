<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Structures\Models\Structure;
use Exception;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends MasterAdminController
{

    public function actionIndex()
    {
        if (CoreFunctions::isAJAX() && !$this->employeeAuthorizator->authorized()) {
            NotificationLog::instance()->pushError('Нет доступа!');
            $this->response->send();
            return;
        }
        $this->authorizeIfNot();

        $frameName = Param::get('name', true)->asString(true, 'Недопустимое имя фрейма!');

        $frameFileName = SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR . $frameName;
        if (!file_exists($frameFileName)) {
            NotificationLog::instance()->pushError("Фрейм с именем \"{$frameName}\" не найден!");
        }

        if (NotificationLog::instance()->hasProblems()) {
            $this->response->send();
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
            NotificationLog::instance()->pushError("Фрейм \"{$frameName}\" нельзя удалять, пока он используется в структуре сайта. На данный момент фрейм назначен разделам: \"" . implode('", "', $structureNames) . '"');
        }

        if (NotificationLog::instance()->hasProblems()) {
            $this->response->send();
            return;
        }

        try {
            unlink($frameFileName);
        } catch (Exception $e) {
            NotificationLog::instance()->pushError('При удалении фрейма произошла ошибка.');
        }

        if (!NotificationLog::instance()->hasProblems()) {
            NotificationLog::instance()->pushMessage("Фрейм \"{$frameName}\" успешно удалён.");
        }

        $this->response->send();
    }

}