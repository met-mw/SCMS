<?php
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
        <form id="page-edit-form" action="/admin/modules/pages/save/" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> статичной страницы</legend>
                <input type="hidden" name="page-edit-id" value="<?= $id ?>" />

                <div class="row">
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label for="page-edit-number">№</label>
                            <input class="form-control input-sm" id="page-edit-number" name="page-edit-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                            <span class="help-block">Номер</span>
                        </div>
                    </div>
                    <div class="col-lg-11">
                        <div class="form-group">
                            <label for="page-edit-name">Наименование</label>
                            <input class="form-control input-sm" id="page-edit-name" name="page-edit-name" type="text" placeholder="Наименование" value="<?= $name ?>">
                            <span class="help-block">Название статичной страницы</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="page-edit-description">Описание</label>
                    <textarea class="form-control input-sm" id="page-edit-description" name="page-edit-description" placeholder="Описание" style="width: 100%; height: 100px;"><?= $description ?></textarea>
                    <span class="help-block">Служебная информация, к заполнению не обязательна.</span>
                </div>

                <div class="form-group">
                    <label for="page-edit-active">Активность</label>
                    <input class="checkbox" name="page-edit-active" id="page-edit-active" title="Доступен ли данный раздел в пользовательской части сайта." type="checkbox"<?= ($active || $isNew ? ' CHECKED' : '') ?>>
                    <span class="help-block">Доступен ли данный раздел в пользовательской части сайта.</span>
                </div>

                <div class="control-group">
                    <label for="page-edit-content">Содержимое</label>
                    <textarea class="form-control input-sm" id="page-edit-content" name="page-edit-content" placeholder="Содержимое"><?= $content ?></textarea>
                    <span class="help-block">Содержимое статичной страницы. Информация, которая будет отображаться на сайте.</span>
                </div>

                <hr/>
                <button id="page-edit-save" name="page-edit-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button id="page-edit-accept" name="page-edit-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
    <?
    }

}