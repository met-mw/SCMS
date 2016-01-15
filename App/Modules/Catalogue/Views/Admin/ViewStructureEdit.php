<?php
namespace App\Modules\Structures\Views\Admin;


use App\Models\Module;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\ViewNotifications;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\View;

class ViewStructureEdit extends View {

    /** @var Module[] */
    public $modulesList;
    /** @var Structure|null */
    public $structure;
    /** @var View */
    public $currentModuleConfigView;
    /** @var Structure[] */
    public $structuresList;
    /** @var int */
    public $parentId;
    /** @var ViewNotifications */
    public $notificationsView;

    public function __construct() {
        $this->notificationsView = new ViewNotifications();

        $this->optional[] = 'currentModuleConfigView';
        $this->optional[] = 'structure';
    }

    public function currentRender() {
        $isNew = is_null($this->structure);

        if (!$isNew) {
            $id = $this->structure->id;
            $name = $this->structure->name;
            $description = $this->structure->description;
            $structureId = $this->structure->structure_id;
            $path = $this->structure->path;
            $frame = $this->structure->frame;
            $moduleId = $this->structure->module_id;
            $anchor = $this->structure->anchor;
            $priority = $this->structure->priority;
            $active = $this->structure->active;
        } else {
            $id = $name = $description = $path = $frame = '';
            $structureId = $this->parentId;
            $moduleId = 0;
            $anchor = 0;
            $priority = 0;
            $active = 1;
        }

        ?>
        <form action="/admin/modules/structures/save/" method="post" id="structure-edit-form">
            <fieldset>
                <legend><?= ($isNew ? 'Добавление' : 'Редактирование') ?> структуры</legend>
                <input type="hidden" id="structure-id" name="structure-id" value="<?= $id ?>" />

                <div class="control-group">
                    <label>№</label>
                    <div class="controls">
                        <input class="edit-form-id" name="structure-number" id="structure-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                    </div>
                    <span class="help-block">Идетификатор структуры, заполняется автоматически, редактированию не подлежит.</span>
                </div>

                <div class="control-group">
                    <label>Наименование</label>
                    <div class="controls">
                        <input class="edit-form-text" name="structure-name" id="structure-name" type="text" placeholder="Наименование" value="<?= $name ?>">
                    </div>
                    <span class="help-block">Отображается в заголовке страницы</span>
                </div>

                <div class="control-group">
                    <label>Описание</label>
                    <div class="controls">
                        <textarea class="edit-form-text" name="structure-description" id="structure-description" placeholder="Описание"><?= $description ?></textarea>
                    </div>
                    <span class="help-block">Служебная информация, к заполнению не обязательна.</span>
                </div>

                <div class="control-group">
                    <label>Родительский раздел</label>
                    <div class="controls">
                        <select name="structure-parent" id="structure-parent">
                            <option title="Родительский раздел не назначен" value="0">Без раздела</option>
                            <? foreach ($this->structuresList as $oStructure): ?>
                                <option title="<?= $oStructure->description ?>" value="<?= $oStructure->id ?>"<?= ($oStructure->id == $structureId ? ' selected="selected"' : '') ?>><?= $oStructure->name ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <span class="help-block">Родительский раздел страницы</span>
                </div>

                <div class="control-group">
                    <label>Путь</label>
                    <div class="controls">
                        <input class="edit-form-text" name="structure-path" id="structure-path" type="text" placeholder="Путь" value="<?= $path ?>">
                    </div>
                    <span class="help-block">Путь к странице в адресной строке.</span>
                </div>

                <div class="control-group">
                    <label>Фрейм</label>
                    <div class="controls">
                        <input class="edit-form-text" name="structure-frame" id="structure-frame" type="text" placeholder="Фрейм" value="<?= $frame ?>">
                    </div>
                    <span class="help-block">Имя фрейма, используемого при отображении страницы.</span>
                </div>

                <div class="control-group">
                    <label>Модуль отображения</label>
                    <div class="controls">
                        <select name="structure-module" id="structure-module">
                            <option title="Модуль для отображения не используется" value="0">Без модуля</option>
                            <? foreach ($this->modulesList as $module): ?>
                                <option title="<?= $module->description ?>" value="<?= $module->id ?>"<?= ($module->id == $moduleId ? ' selected="selected"' : '') ?>><?= $module->alias ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <span class="help-block">Модуль отображения страницы</span>
                </div>

                <div class="control-group" id="module-controls-container">
                    <? if ($this->currentModuleConfigView): ?>
                        <? $this->currentModuleConfigView->render(); ?>
                    <? endif; ?>
                </div>

                <div class="checkbox">
                    <label>
                        <input name="structure-anchor" id="structure-anchor" type="checkbox"<?= ($anchor ? ' CHECKED' : '') ?>> фрагмент родительской страницы
                    </label>
                </div>

                <div class="control-group">
                    <label>Приоритет</label>
                    <div class="controls">
                        <input class="edit-form-id" name="structure-priority" type="number" placeholder="Приоритет" value="<?= $priority ?>">
                    </div>
                    <span class="help-block">Отвечает за порядок вывода элементов структуры в меню.</span>
                </div>

                <div class="checkbox">
                    <label>
                        <input name="structure-active" type="checkbox"<?= ($active || $isNew ? ' CHECKED' : '') ?>> активна
                    </label>
                </div>

                <hr/>
                <button name="structure-save" id="structure-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button name="structure-accept" id="structure-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
        $this->notificationsView->render();
    }

}