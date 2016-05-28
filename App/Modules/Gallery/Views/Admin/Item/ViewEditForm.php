<?php
namespace App\Modules\Gallery\Views\Admin\Item;


use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryItem;
use SFramework\Classes\View;

class ViewEditForm extends View
{

    /** @var Gallery */
    public $Gallery;
    /** @var GalleryItem */
    public $GalleryItem;
    /** @var string */
    public $backUrl;

    public function __construct()
    {
        $this->optional[] = 'galleryItem';
    }

    public function currentRender()
    {
        $isNew = is_null($this->GalleryItem->id);

        if (!$isNew) {
            $id = $this->GalleryItem->id;
            $name = $this->GalleryItem->name;
            $description = $this->GalleryItem->description;
            $path = $this->GalleryItem->path;
            $position = $this->GalleryItem->position;
        } else {
            $id = $name = $description = $path = $position ='';
        }

        $galleryId = $this->Gallery->id;

        ?>
        <form id="gallery-item-edit-form" action="/admin/modules/gallery/item/save/" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> элемента галлереи</legend>
                <input type="hidden" name="gallery-item-edit-id" value="<?= $id ?>" />
                <input type="hidden" name="gallery-id" value="<?= $galleryId ?>" />

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="gallery-item-edit-number">№</label>
                                    <input class="form-control input-sm" id="gallery-item-edit-number" name="gallery-item-edit-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                                    <span class="help-block">Номер</span>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="gallery-item-edit-name">Наименование</label>
                                    <input class="form-control input-sm" id="gallery-item-edit-name" name="gallery-item-edit-name" type="text" placeholder="Название" value="<?= $name ?>">
                                    <span class="help-block">Название элемента галлереи</span>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="gallery-item-edit-position">Позиция</label>
                                    <input class="form-control input-sm" id="gallery-item-edit-position" name="gallery-item-edit-position" min="0" type="number" placeholder="Позиция" value="<?= $position ?>">
                                    <span class="help-block">Позиция</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gallery-item-edit-description">Описание</label>
                            <textarea class="form-control input-sm" id="gallery-item-edit-description" name="gallery-item-edit-description" placeholder="Описание" style="width: 100%; height: 100px;"><?= $description ?></textarea>
                            <span class="help-block">Описание элемента галлереи</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="row">
                            <? $href = $path == '' ? '/public/assets/images/system/no-image.svg' : $path; ?>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="gallery-item-edit-path">Изображение</label>
                                    <div class="input-append">
                                        <input type="hidden" id="gallery-item-edit-path" name="gallery-item-edit-path" value="<?= $href ?>" />
                                        <a href="/filemanager/dialog.php?type=1&field_id=gallery-item-edit-path" class="btn btn-success btn-sm gallery-item-edit-path-btn" type="button">Выбрать изображение</a>
                                        <button id="gallery-item-edit-path-remove-btn" type="button" class="btn btn-danger btn-sm" title="Убрать изображение"><span class="glyphicon glyphicon-remove"></span></button>
                                    </div>
                                    <hr/>
                                    <span class="help-block">Выбор изображения элемента галлереи. Данное изображение будет отображаться в пользовательской части сайта в качестве элемента галлереи.</span>
                                </div>
                            </div>
                            <div class="col-lg-7 text-center">
                                <a id="gallery-item-edit-path-a" href="<?= $href ?>" class="fancybox"><img id="gallery-item-edit-path-img" class="fancybox-image" src="<?= $href ?>" alt="Изображение"/></a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <a href="<?= $this->backUrl ?>" class="btn btn-warning">Отмена</a>
                <button id="gallery-item-edit-save" name="gallery-item-edit-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button id="gallery-item-edit-accept" name="gallery-item-edit-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
    }

}