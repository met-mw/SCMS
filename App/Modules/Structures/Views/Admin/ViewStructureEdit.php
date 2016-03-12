<?php
namespace App\Modules\Structures\Views\Admin;


use App\Models\Module;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\ViewNotificationsModal;
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
    /** @var ViewNotificationsModal */
    public $notificationsView;

    public function __construct() {
        $this->notificationsView = new ViewNotificationsModal();

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
            $seoTitle = $this->structure->seo_title;
            $seoDescription = $this->structure->seo_description;
            $seoKeywords = $this->structure->seo_keywords;
        } else {
            $id = $name = $description = $path = $frame = $seoTitle = $seoDescription = $seoKeywords = '';
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

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="structure-number">№</label>
                                    <input class="form-control input-sm" name="structure-number" id="structure-number" disabled="disabled" type="number" placeholder="№" value="<?= $id ?>">
                                    <span class="help-block">Номер</span>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="structure-name">Наименование</label>
                                    <input class="form-control input-sm" name="structure-name" id="structure-name" type="text" placeholder="Наименование" value="<?= $name ?>">
                                    <span class="help-block">Отображается в заголовке страницы</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="structure-priority">Приоритет</label>
                                    <input class="form-control input-sm" name="structure-priority" id="structure-priority" title="Отвечает за порядок вывода элементов структуры в меню." type="number" placeholder="Приоритет" value="<?= $priority ?>">
                                    <span class="help-block">Сортировка в списке</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="structure-description">Описание</label>
                                    <textarea class="form-control input-sm" rows="5" name="structure-description" id="structure-description" placeholder="Описание"><?= $description ?></textarea>
                                    <span class="help-block">Служебная информация, к заполнению не обязательна.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="structure-path">Путь</label>
                                    <input class="form-control input-sm" name="structure-path" id="structure-path" type="text" placeholder="Путь" value="<?= $path ?>">
                                    <span class="help-block">Путь к странице в адресной строке.</span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="structure-parent">Родительский раздел</label>
                                    <select class="form-control input-sm" name="structure-parent" id="structure-parent">
                                        <option title="Родительский раздел не назначен" value="0">Без раздела</option>
                                        <? foreach ($this->structuresList as $oStructure): ?>
                                            <option title="<?= $oStructure->description ?>" value="<?= $oStructure->id ?>"<?= ($oStructure->id == $structureId ? ' selected="selected"' : '') ?>><?= $oStructure->name ?></option>
                                        <? endforeach; ?>
                                    </select>
                                    <span class="help-block">Родительский раздел страницы</span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="structure-frame">Фрейм</label>
                                    <input class="form-control input-sm" name="structure-frame" id="structure-frame" type="text" placeholder="Фрейм" value="<?= $frame ?>">
                                    <span class="help-block">Имя фрейма, используемого при отображении страницы.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="structure-seo-title">SEO заголовок</label>
                                    <textarea class="form-control input-sm" rows="5" name="structure-seo-title" id="structure-seo-title" placeholder="SEO заголовок"><?= $seoTitle ?></textarea>
                                    <span class="help-block">Заголовок раздела для поисковых систем.</span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="structure-seo-description">SEO описание</label>
                                    <textarea class="form-control input-sm" rows="5" name="structure-seo-description" id="structure-seo-description" placeholder="SEO описание"><?= $seoDescription ?></textarea>
                                    <span class="help-block">Описание раздела для поисковых систем.</span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="structure-seo-keywords">SEO ключевые слова</label>
                                    <textarea class="form-control input-sm" rows="5" name="structure-seo-keywords" id="structure-seo-keywords" placeholder="SEO ключевые слова"><?= $seoKeywords ?></textarea>
                                    <span class="help-block">Ключевые слова раздела для поисковых систем.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="structure-module">Модуль отображения</label>
                            <select class="form-control input-sm" name="structure-module" id="structure-module">
                                <option title="Модуль для отображения не используется" value="0">Без модуля</option>
                                <? foreach ($this->modulesList as $module): ?>
                                    <option title="<?= $module->description ?>" value="<?= $module->id ?>"<?= ($module->id == $moduleId ? ' selected="selected"' : '') ?>><?= $module->alias ?></option>
                                <? endforeach; ?>
                            </select>
                            <span class="help-block">Модуль отображения страницы</span>
                        </div>
                        <div class="alert alert-info" id="module-controls-container">
                            <? if ($this->currentModuleConfigView): ?>
                                <? $this->currentModuleConfigView->render(); ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="structure-anchor">Фрагмент структуры</label>
                                    <input class="checkbox" name="structure-anchor" id="structure-anchor" title="Является ли данный элемент сруктуры фрагментом родительской структуры." type="checkbox"<?= ($anchor ? ' CHECKED' : '') ?>>
                                    <span class="help-block">Является ли данный элемент сруктуры фрагментом родительской структуры.</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="structure-active">Активность</label>
                                    <input class="checkbox" name="structure-active" id="structure-active" title="Доступен ли данный раздел в пользовательской части сайта." type="checkbox"<?= ($active || $isNew ? ' CHECKED' : '') ?>>
                                    <span class="help-block">Доступен ли данный раздел в пользовательской части сайта.</span>
                                </div>
                            </div>
                        </div>
                    </div>
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