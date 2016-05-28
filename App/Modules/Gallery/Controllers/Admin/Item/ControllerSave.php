<?php
namespace App\Modules\Gallery\Controllers\Admin\Item;


use App\Classes\AdministratorAreaController;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryItem;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends AdministratorAreaController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $galleryId = Param::post('gallery-id')->asInteger(true, 'Недопустимое значние номера галлереи.');
        $galleryItemId = Param::post('gallery-item-edit-id', false)->asInteger(false);

        $name = Param::post('gallery-item-edit-name')
            ->noEmpty('Поле "Название" должно быть заполнено.')
            ->asString();
        $description = Param::post('gallery-item-edit-description')
            ->asString();
        $path = Param::post('gallery-item-edit-path')
            ->noEmpty('Недопустимое значение пути к изображению.')
            ->asString();
        $position = Param::post('gallery-item-edit-position')
            ->noEmpty('Недопустимое значение позиции элемента.')
            ->asInteger();

        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $galleryId);
        if ($oGallery->isNew()) {
            NotificationLog::instance()->pushError("Попытка добавить элемент в несуществующую галлерею.");
        }

        if (!NotificationLog::instance()->hasProblems()) {
            /** @var GalleryItem $oGalleryItem */
            $oGalleryItem = DataSource::factory(GalleryItem::cls(), $galleryItemId == 0 ? null : $galleryItemId);
            $oGalleryItem->name = $name;
            $oGalleryItem->description = $description;
            $oGalleryItem->path = $path;
            $oGalleryItem->gallery_id = $oGallery->id;
            $oGalleryItem->position = $position;

            $oGalleryItem->commit();

            NotificationLog::instance()->pushMessage("Элемент \"{$oGalleryItem->name}\" успешно " . ($galleryItemId == 0 ? "добавлен в галлерею \"{$oGalleryItem->getGallery()->name}\"" : 'отредактирован') . '.');
            $redirect = '';
            if (Param::post('gallery-item-edit-accept', false)->exists()) {
                $redirect = "/admin/modules/gallery/item/?gallery_id={$oGalleryItem->gallery_id}";
            } elseif ($galleryItemId == 0) {
                $redirect = "/admin/modules/gallery/item/edit/?id={$oGalleryItem->getPrimaryKey()}";
            }
            $this->Response->send($redirect);
        } else {
            $this->Response->send();
        }
    }

}