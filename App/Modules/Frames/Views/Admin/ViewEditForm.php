<?php
namespace App\Modules\Frames\Views\Admin;


use App\Views\Admin\ViewResponse;
use SFramework\Classes\View;

class ViewEditForm extends View {

    public $frameName;
    public $backUrl;

    public function __construct() {
        $this->optional = ['frameName'];
    }

    public function currentRender() {
        $isNew = is_null($this->frameName);

        $action = '/admin/modules/frames/save/';
        if (!$isNew) {
            $content = file_get_contents(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR . $this->frameName);
        } else {
            $content = '';
        }

        ?>

        <form action="<?= $action ?>" method="post" id="frame-form">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> Фрейма</legend>

                <div class="form-group">
                    <label for="frame-name">Наименование</label>
                    <input class="form-control" name="frame-name" type="text" placeholder="Наименование" value="<?= $this->frameName ?>">
                    <span class="help-block">Название файла фрейма</span>
                </div>

                <div class="form-group">
                    <label for="frame-content">Содержимое</label>
                    <textarea id="frame-content" class="form-control" style="width: 100%; height: 600px;" name="frame-content" placeholder="Содержимое"><?= $content ?></textarea>
                    <span class="help-block">Сожержимое фрейма</span>
                </div>

                <hr/>
                <a href="<?= $this->backUrl ?>" class="btn btn-warning">Отмена</a>
                <button id="frame-save" name="frame-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button id="frame-accept" name="frame-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
    }

}