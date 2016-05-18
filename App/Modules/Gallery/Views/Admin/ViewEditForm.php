<?php
namespace App\Modules\Gallery\Views\Admin;


use App\Modules\Gallery\Models\Admin\Gallery;
use SFramework\Classes\View;

class ViewEditForm extends View
{

    protected $optional = ['gallery'];

    /** @var Gallery */
    public $gallery;

    public function currentRender() {
        $isNew = is_null($this->gallery->id);

        if (!$isNew) {
            $id = $this->gallery->id;
            $name = $this->gallery->name;
            $description = $this->gallery->description;
        } else {
            $id = $name = $description = '';
        }

        ?>
        <form id="gallery-edit-form" action="/admin/modules/gallery/save/" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> галлереи</legend>
                <input type="hidden" name="gallery-edit-id" value="<?= $id ?>" />

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

                <hr/>
                <button id="gallery-edit-save" name="gallery-edit-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button id="gallery-edit-accept" name="gallery-edit-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
    }

}