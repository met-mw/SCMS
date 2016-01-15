<?php
/**
 * Created by PhpStorm.
 * User: metr
 * Date: 22.11.15
 */

namespace App\Modules\Pages\Views\Admin;


use App\Modules\Pages\Models\Page;
use App\Views\Admin\ViewResponse;
use SFramework\Classes\View;

class ViewEditForm extends View {

    protected $optional = ['page'];

    /** @var Page|null */
    public $page;

    public function currentRender() {
        $isNew = is_null($this->page);

        if (!$isNew) {
            $id = $this->page->id;
            $name = $this->page->name;
            $description = $this->page->description;
            $content = $this->page->content;
            $active = $this->page->active;
        } else {
            $id = $name = $description = $content = '';
            $active = 1;
        }

        ?>
        <form id="page-form" action="/admin/modules/pages/save/" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> статичной страницы</legend>
                <input type="hidden" name="page-id" value="<?= $id ?>" />

                <div class="control-group">
                    <label>№</label>
                    <div class="controls">
                        <input class="edit-form-id" name="page-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                    </div>
                    <span class="help-block">Идетификатор статичной страницы, заполняется автоматически, редактированию не подлежит.</span>
                </div>

                <div class="control-group">
                    <label>Наименование</label>
                    <div class="controls">
                        <input class="edit-form-text" name="page-name" type="text" placeholder="Наименование" value="<?= $name ?>">
                    </div>
                    <span class="help-block">Название статичной страницы</span>
                </div>

                <div class="control-group">
                    <label>Описание</label>
                    <div class="controls">
                        <textarea class="edit-form-text" name="page-description" placeholder="Описание" style="width: 100%; height: 100px;"><?= $description ?></textarea>
                    </div>
                    <span class="help-block">Служебная информация, к заполнению не обязательна.</span>
                </div>

                <div class="control-group">
                    <label>Содержимое</label>
                    <div class="controls">
                        <textarea id="page-content" class="edit-form-text" name="page-content" placeholder="Содержимое"><?= $content ?></textarea>
                    </div>
                    <span class="help-block">Содержимое статичной страницы. Информация, которая будет отображаться на сайте.</span>
                </div>

                <button id="page-save" name="page-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button id="page-accept" name="page-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
    <?
    }

}