<?php
namespace App\Modules\Frames\Views\Admin;


use App\Views\Admin\ViewResponse;
use SFramework\Classes\View;

class ViewEditForm extends View {

    public $frameName;
    /** @var ViewResponse */
    public $response;

    public function __construct() {
        $this->optional = ['frameName', 'response'];
    }

    public function currentRender() {
        $isNew = is_null($this->frameName);

        $action = '/admin/modules/frames/save/';
        if (!$isNew) {
            $content = file_get_contents(SFW_APP_ROOT . 'Frames' . DIRECTORY_SEPARATOR . $this->frameName);
        } else {
            $content = '';
        }

        if ($this->response) {
            $this->response->render();
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#frame-content").markItUp(mySettings);
            });
        </script>

        <form action="<?= $action ?>" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> структуры</legend>
                <? if ($isNew): ?>
                <input type="hidden" name="frame-name" value="<?= $this->frameName ?>" />
                <? endif; ?>

                <div class="control-group">
                    <label>Наименование</label>
                    <div class="controls">
                        <input class="edit-form-text" name="frame-name" type="text" placeholder="Наименование" value="<?= $this->frameName ?>">
                    </div>
                    <span class="help-block">Название файла фрейма</span>
                </div>

                <div class="control-group">
                    <label>Содержимое</label>
                    <div class="controls">
                        <textarea id="frame-content" class="edit-form-text" name="frame-content" placeholder="Содержимое"><?= $content ?></textarea>
                    </div>
                    <span class="help-block">Сожержимое фрейма</span>
                </div>

                <button name="frame-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button name="frame-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
    }

}