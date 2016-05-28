<?php


namespace App\Modules\Gallery\Controllers\Admin\Item;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\Admin\GalleryItem;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryItemId = Param::get('id')
            ->noEmpty('Не задан обязательный параметр.')
            ->asInteger(true, 'Параметр должен быть числом.');

        /** @var GalleryItem $oGalleryItem */
        $oGalleryItem = DataSource::factory(GalleryItem::cls(), $galleryItemId);
        if (is_null($oGalleryItem) || !$oGalleryItem->getPrimaryKey()) {
            NotificationLog::instance()->pushError('Элемент галлереи не найден.');
        } else {
            NotificationLog::instance()->pushMessage("Элемент \"{$oGalleryItem->name}\" галлереи {$oGalleryItem->getGallery()->name} успешно удален.");
            $oGalleryItem->delete();
        }

        $this->Response->send();
    }

}