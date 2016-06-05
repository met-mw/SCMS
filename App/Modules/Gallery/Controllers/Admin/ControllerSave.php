<?php
namespace App\Modules\Gallery\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\Gallery;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->needAuthenticate();

        $galleryId = Param::post('gallery-edit-id', false)->asInteger(false);

        $name = Param::post('gallery-edit-name')
            ->noEmpty('Поле "Название" должно быть заполнено.')
            ->asString();
        $description = Param::post('gallery-edit-description')
            ->asString();

        if (!SCMSNotificationLog::instance()->hasProblems()) {
            /** @var Gallery $oGallery */
            $oGallery = DataSource::factory(Gallery::cls(), $galleryId == 0 ? null : $galleryId);
            $oGallery->name = $name;
            $oGallery->description = $description;
            $oGallery->deleted = false;

            $oGallery->commit();

            SCMSNotificationLog::instance()->pushMessage("Галерея \"{$oGallery->name}\" успешно " . ($galleryId == 0 ? 'добавлена' : 'отредактирована') . '.');
            $redirect = '';
            if (Param::post('gallery-edit-accept', false)->exists()) {
                $redirect = '/admin/modules/gallery/';
            } elseif ($galleryId == 0) {
                $redirect = "/admin/modules/gallery/edit/?id={$oGallery->getPrimaryKey()}";
            }
            $this->Response->send($redirect);
        } else {
            $this->Response->send();
        }
    }

}