<?php
namespace App\Modules\Gallery\Views\Admin;


use App\Modules\Gallery\Models\Gallery;
use SFramework\Classes\View;

class ViewEditForm extends View
{

    protected $optional = ['gallery'];

    /** @var Gallery */
    public $gallery;
    public $backUrl;

    public function currentRender() {
        $isNew = is_null($this->gallery->id);

        if (!$isNew) {
            $id = $this->gallery->id;
            $name = $this->gallery->name;
            $description = $this->gallery->description;
            $aGalleryItems = $this->gallery->getGalleryItems('position');
        } else {
            $id = $name = $description = '';
            $aGalleryItems = [];
        }

        ?>
        <style>
            .galleria {
                height: 300px;
            }

            .thumb img {
                filter: none; /* IE6-9 */
                -webkit-filter: grayscale(0);
                border-radius:5px;
                background-color: #fff;
                border: 1px solid #ddd;
                padding:5px;
            }
            .thumb img:hover {
                filter: gray; /* IE6-9 */
                -webkit-filter: grayscale(1);
            }
            .thumb {
                padding:5px;
            }
        </style>
        <form id="gallery-edit-form" action="/admin/modules/gallery/save/" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление новой галлереи' : "Редактирование галлереи (<a target=\"_blank\" href=\"/admin/modules/gallery/item/?gallery_id={$id}\">перейти к редактированию элементов</a>)") ?></legend>
                <input type="hidden" name="gallery-edit-id" value="<?= $id ?>" />

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="gallery-edit-number">№</label>
                                    <input class="form-control input-sm" id="gallery-edit-number" name="gallery-edit-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                                    <span class="help-block">Номер</span>
                                </div>
                            </div>
                            <div class="col-lg-11">
                                <div class="form-group">
                                    <label for="gallery-edit-name">Наименование</label>
                                    <input class="form-control input-sm" id="gallery-edit-name" name="gallery-edit-name" type="text" placeholder="Наименование" value="<?= $name ?>">
                                    <span class="help-block">Название галлереи</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gallery-edit-description">Описание</label>
                            <textarea class="form-control input-sm" id="gallery-edit-description" name="gallery-edit-description" placeholder="Описание" style="width: 100%; height: 100px;"><?= $description ?></textarea>
                            <span class="help-block">Служебная информация, к заполнению не обязательна.</span>
                        </div>
                    </div>

                    <? if (!empty($aGalleryItems)): ?>
                    <div class="col-sm-6">
                        <h4>Режим галлереи с прокруткой</h4>
                        <div class="galleria">
                            <? foreach ($aGalleryItems as $oGalleryItem): ?>
                                <a href="<?= $oGalleryItem->path ?>">
                                    <img src="<?= $oGalleryItem->path ?>" data-big="<?= $oGalleryItem->path ?>" data-title="<?= $oGalleryItem->name ?>" data-description="<?= $oGalleryItem->description ?>" />
                                </a>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <? endif; ?>
                </div>
                <? if (!empty($aGalleryItems)): ?>
                <hr/>
                <div class="row">
                    <h4 class="text-center">Режим галлереи с плитками</h4>

                    <div class="col-xs-12">
                        <div class="container">
                            <div class="row">
                                <? foreach ($aGalleryItems as $oGalleryItem): ?>
                                    <div class="col-md-3 col-sm-4 col-xs-6 thumb">
                                        <a class="fancyimage" rel="group" href="<?= $oGalleryItem->path ?>">
                                            <img class="img-responsive" src="<?= $oGalleryItem->path ?>" />
                                        </a>
                                    </div>
                                <? endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <? endif; ?>

                <hr/>
                <a href="<?= $this->backUrl ?>" class="btn btn-warning">Отмена</a>
                <button id="gallery-edit-save" name="gallery-edit-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button id="gallery-edit-accept" name="gallery-edit-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
    }

}