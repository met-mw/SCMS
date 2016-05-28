<?php
namespace App\Modules\Gallery\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\Admin\Gallery;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryId = Param::post('gallery-edit-id', false)->asInteger(false);

        $name = Param::post('gallery-edit-name')
            ->noEmpty('Поле "Наименование" должно быть заполнено.')
            ->asString();
        $description = Param::post('gallery-edit-description')
            ->asString();

        if (!NotificationLog::instance()->hasProblems()) {
            /** @var Gallery $oGallery */
            $oGallery = DataSource::factory(Gallery::cls(), $galleryId == 0 ? null : $galleryId);
            $oGallery->name = $name;
            $oGallery->description = $description;

            $oGallery->commit();

            NotificationLog::instance()->pushMessage("Галлерея \"{$oGallery->name}\" успешно " . ($galleryId == 0 ? 'добавлена' : 'отредактирована') . '.');
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