<?php
namespace App\Modules\Catalogue\Views\Admin;


use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\View;

class ViewItemEdit extends View {

    /** @var Item */
    public $oItem;
    /** @var Category[] */
    public $aCategories;
    /** @var int */
    public $parentId;

    public function __construct() {
        $this->optional[] = 'parentId';
    }

    public function currentRender() {
        ?>
        <form action="/admin/modules/catalogue/save/" method="post" id="catalogue-category-edit-form">
            <fieldset>
                <legend>Редактирование категории</legend>
                <input type="hidden" id="catalogue-category-id" name="catalogue-category-id" value="<?= $this->oCategory->getPrimaryKey() ?>" />

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="catalogue-category-number">№</label>
                                    <input class="form-control input-sm" name="catalogue-category-number" id="catalogue-category-number" disabled="disabled" type="number" placeholder="№" value="<?= $this->oCategory->getPrimaryKey() ?>">
                                    <span class="help-block">Номер</span>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="catalogue-category-name">Наименование</label>
                                    <input class="form-control input-sm" name="catalogue-category-name" id="catalogue-category-name" type="text" placeholder="Наименование" value="<?= $this->oCategory->name ?>">
                                    <span class="help-block">Наименование категории элементов</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="catalogue-category-description">Описание</label>
                                    <textarea class="form-control input-sm" name="catalogue-category-description" id="catalogue-category-description" placeholder="Описание"><?= $this->oCategory->description ?></textarea>
                                    <span class="help-block">Описание категории элементов</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="catalogue-category-parent_id">Родительская категория</label>
                                    <select class="form-control input-sm" name="catalogue-category-parent_id" id="catalogue-category-parent_id">
                                        <option value="0">Не выбрана</option>
                                        <? foreach ($this->aCategories as $oCategory): ?>
                                            <option value="<?= $oCategory->id ?>"<?= ($oCategory->id == $this->parentId ? ' selected="selected"' : '') ?>><?= "({$oCategory->id}) {$oCategory->name}" ?></option>
                                        <? endforeach; ?>
                                    </select>
                                    <span class="help-block">Категория, в которой находится данная категория</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="catalogue-category-priority">Приоритет</label>
                                    <input class="form-control input-sm" name="catalogue-category-priority" id="catalogue-category-priority" title="Отвечает за порядок вывода элементов структуры в меню." type="number" placeholder="Приоритет" value="<?= $this->oCategory->priority ?>">
                                    <span class="help-block">Сортировка в списке</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="catalogue-category-active">Активность</label>
                                    <input class="checkbox" name="catalogue-category-active" id="catalogue-category-active" title="Доступен ли данный раздел в пользовательской части сайта." type="checkbox"<?= (!$this->oCategory->getPrimaryKey() || $this->oCategory->active ? ' CHECKED' : '') ?>>
                                    <span class="help-block">Доступна ли данная категория в пользовательской части сайта.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <button name="catalogue-category-save" id="catalogue-category-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button name="catalogue-category-accept" id="catalogue-category-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
        <?
    }

}