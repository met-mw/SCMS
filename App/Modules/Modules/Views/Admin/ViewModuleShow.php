<?php
namespace App\Modules\Modules\Views\Admin;


use App\Models\Module;
use SFramework\Classes\View;

class ViewModuleShow extends View {

    protected $optional = ['module'];

    /** @var Module|null */
    public $module;

    public function currentRender() {
        $isNew = is_null($this->module);

        if (!$isNew) {
            $id = $this->module->id;
            $name = $this->module->name;
            $alias = $this->module->alias;
            $description = $this->module->description;
            $active = $this->module->active;

            $action = ($active == 1 ? '/admin/modules/activate/' : '/admin/modules/deactivate/');
        } else {
            $id = $name = $alias = $description = '';
            $active = null;

            $action = '/admin/modules/add/';
        }

        ?>
        <form action="<?= $action ?>" method="post">
            <fieldset>
                <legend><?= ($isNew ? 'Установка' : 'Просмотр') ?> модуля</legend>
                <input type="hidden" name="module-id" value="<?= $id ?>" />

                <div class="control-group">
                    <label>№</label>
                    <div class="controls">
                        <input class="edit-form-id" name="module-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                    </div>
                    <span class="help-block">Идетификатор модуля, заполняется автоматически, редактированию не подлежит.</span>
                </div>

                <div class="control-group">
                    <label>Наименование</label>
                    <div class="controls">
                        <input class="edit-form-text" name="module-name" type="text" placeholder="Наименование" value="<?= $name ?>">
                    </div>
                    <span class="help-block">Фактическое название модуля</span>
                </div>

                <div class="control-group">
                    <label>Псевдоним</label>
                    <div class="controls">
                        <input class="edit-form-text" name="module-name" type="text" placeholder="Псевдоним" value="<?= $alias ?>">
                    </div>
                    <span class="help-block">Отображаемое название модуля</span>
                </div>

                <div class="control-group">
                    <label>Описание</label>
                    <div class="controls">
                        <textarea class="edit-form-text" name="module-description" placeholder="Описание"><?= $description ?></textarea>
                    </div>
                    <span class="help-block">Служебная информация, к заполнению не обязательна.</span>
                </div>

                <? if (is_null($active)): ?>
                    <button name="module-accept" type="submit" class="btn btn-success">Установить</button>
                <? else: ?>
                    <? if ($active == 1): ?>
                        <button name="structure-deactivate" type="submit" class="btn btn-success">Деактивировать</button>
                    <? else: ?>
                        <button name="structure-activate" type="submit" class="btn btn-danger">Активировать</button>
                    <? endif; ?>
                <? endif; ?>
            </fieldset>
        </form>
    <?
    }

}