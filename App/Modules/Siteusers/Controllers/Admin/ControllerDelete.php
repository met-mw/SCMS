<?php
namespace App\Modules\Siteusers\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Siteusers\Models\Siteuser;
use Exception;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends MasterAdminController
{

    public function actionIndex()
    {
        if (CoreFunctions::isAJAX()) {
            if (!$this->employeeAuthorizator->authorized()) {
                NotificationLog::instance()->pushError('Нет доступа.');
                $this->response->send();
                return;
            }
        } else {
            $this->authorizeIfNot();
        }

        $siteuserId = Param::get('id')
            ->noEmpty('Параметр обязателен для заполнения.')
            ->asInteger(true, "Неверно задан параметр.");
        /** @var Siteuser $oSiteuser */
        $oSiteuser = DataSource::factory(Siteuser::cls(), $siteuserId);
        if ($oSiteuser->id) {
            $oSiteuser->deleted = true;
            try {
                $oSiteuser->commit();
                NotificationLog::instance()->pushMessage("Пользователь \"$oSiteuser->name\" успешно удалён.");
            } catch (Exception $e) {
                NotificationLog::instance()->pushError($e->getMessage());
            }
        } else {
            NotificationLog::instance()->pushError("Пользователь с ID {$siteuserId} не найден");
        }


        $this->response->send();
    }

}