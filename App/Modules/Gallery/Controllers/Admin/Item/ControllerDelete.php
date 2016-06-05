<?php


namespace App\Modules\Gallery\Controllers\Admin\Item;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\GalleryItem;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->needAuthenticate();

        $galleryItemId = Param::get('id')
            ->noEmpty('Не задан обязательный параметр.')
            ->asInteger(true, 'Параметр должен быть числом.');

        /** @var GalleryItem $oGalleryItem */
        $oGalleryItem = DataSource::factory(GalleryItem::cls(), $galleryItemId);
        if (is_null($oGalleryItem) || !$oGalleryItem->getPrimaryKey()) {
            SCMSNotificationLog::instance()->pushError('Элемент галереи не найден.');
        } else {
            SCMSNotificationLog::instance()->pushMessage("Элемент \"{$oGalleryItem->name}\" галереи {$oGalleryItem->getGallery()->name} успешно удален.");
            $oGalleryItem->delete();
        }

        $this->Response->send();
    }

}