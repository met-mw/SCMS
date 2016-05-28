<?php
namespace App\Modules\Gallery\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\Gallery;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryId = Param::get('id')
            ->noEmpty('Не задан обязательный параметр.')
            ->asInteger(true, 'Параметр должен быть числом.');

        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $galleryId);
        if (is_null($oGallery) || !$oGallery->getPrimaryKey()) {
            NotificationLog::instance()->pushError('Галлерея не найдена.');
        } else {
            NotificationLog::instance()->pushMessage("Галлерея \"$oGallery->name\" успешно удалена.");
            $oGallery->delete();
        }

        $this->Response->send();
    }

}