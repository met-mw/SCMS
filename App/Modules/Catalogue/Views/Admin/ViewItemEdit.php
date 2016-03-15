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
    public $backUrl;

    public function __construct() {
        $this->optional[] = 'parentId';
    }

    public function currentRender() {
        ?>
        <form action="/admin/modules/catalogue/save/item/<?= $this->parentId ? "?parent_pk={$this->parentId}" : '' ?>" method="post" id="catalogue-item-edit-form">
            <fieldset>
                <legend><? if ($this->oItem->isNew()): ?>Добавление позиции<? else: ?>Редактирование позиции "<?= $this->oItem->name ?>"<? endif; ?></legend>
                <input type="hidden" id="catalogue-item-id" name="catalogue-item-id" value="<?= $this->oItem->getPrimaryKey() ?>" />

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="catalogue-item-number">№</label>
                                    <input class="form-control input-sm" name="catalogue-item-number" id="catalogue-item-number" disabled="disabled" type="number" placeholder="№" value="<?= $this->oItem->getPrimaryKey() ?>">
                                    <span class="help-block">Номер</span>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="catalogue-item-name">Наименование</label>
                                    <input class="form-control input-sm" name="catalogue-item-name" id="catalogue-item-name" type="text" placeholder="Наименование" value="<?= $this->oItem->name ?>" title="Наименование позиции">
                                    <span class="help-block">Наименование позиции</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="catalogue-item-parent_id">Родительская категория</label>
                                    <select class="form-control input-sm" name="catalogue-item-parent_id" id="catalogue-item-parent_id">
                                        <option value="0">Не выбрана</option>
                                        <? foreach ($this->aCategories as $oCategory): ?>
                                            <option value="<?= $oCategory->id ?>"<?= ($oCategory->id == $this->parentId ? ' selected="selected"' : '') ?>><?= "({$oCategory->id}) {$oCategory->name}" ?></option>
                                        <? endforeach; ?>
                                    </select>
                                    <span class="help-block">Категория, в которой находится данная категория</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="catalogue-item-price">Цена</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-ruble"></span></span>
                                        <input class="form-control input-sm" name="catalogue-item-price" id="catalogue-item-price" type="number" min="0" step="0.01" placeholder="Цена" value="<?= $this->oItem->price ?>" title="Стоимость товара">
                                    </div>
                                    <span class="help-block">Стоимость товара</span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="catalogue-item-count">Количество</label>
                                    <input class="form-control input-sm" name="catalogue-item-count" id="catalogue-item-count" type="number" min="-1" placeholder="Количество" value="<?= $this->oItem->count ?>" title="Количество экземпляров на складе.">
                                    <span class="help-block">Количество экземпляров на складе.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="catalogue-item-description">Описание</label>
                                    <textarea class="form-control input-sm" name="catalogue-item-description" id="catalogue-item-description" placeholder="Описание" title="Описание позиции"><?= $this->oItem->description ?></textarea>
                                    <span class="help-block">Описание позиции</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="catalogue-item-priority">Приоритет</label>
                                    <input class="form-control input-sm" name="catalogue-item-priority" id="catalogue-item-priority" title="Отвечает за порядок вывода элементов в списке позиций." type="number" placeholder="Приоритет" value="<?= $this->oItem->priority ?>">
                                    <span class="help-block">Отвечает за порядок вывода элементов в списке позиций.</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="catalogue-item-active">Активность</label>
                                    <input class="checkbox" name="catalogue-item-active" id="catalogue-item-active" title="Доступна ли данная позиция в пользовательской части сайта." type="checkbox"<?= (!$this->oItem->getPrimaryKey() || $this->oItem->active ? ' CHECKED' : '') ?>>
                                    <span class="help-block">Доступна ли данная позиция в пользовательской части сайта.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <? $href = $this->oItem->thumbnail == '' ? '/public/assets/images/system/no-image.svg' : $this->oItem->thumbnail; ?>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label for="catalogue-item-thumbnail">Миниатюра</label>
                                    <div class="input-append">
                                        <input type="hidden" id="catalogue-item-thumbnail" name="catalogue-item-thumbnail" value="<?= $href ?>" />
                                        <a href="/filemanager/dialog.php?type=1&field_id=catalogue-item-thumbnail" class="btn btn-success btn-sm catalogue-item-thumbnail-btn" type="button">Выбрать изображение</a>
                                        <button id="catalogue-item-thumbnail-remove-btn" type="button" class="btn btn-danger btn-sm" title="Убрать изображение"><span class="glyphicon glyphicon-remove"></span></button>
                                    </div>
                                    <hr/>
                                    <span class="help-block">Выбор миниатюры позиции. Данное изображение будет отображаться в пользовательской части сайта в качестве иконки позиции.</span>
                                </div>
                            </div>
                            <div class="col-lg-7 text-center">
                                <a id="catalogue-item-thumbnail-a" href="<?= $href ?>" class="fancybox"><img id="catalogue-item-thumbnail-img" class="fancybox-image" src="<?= $href ?>" alt="Изображение"/></a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <a href="<?= $this->backUrl ?>" class="btn btn-warning">Отмена</a>
                <button name="catalogue-item-save" id="catalogue-item-save" type="submit" class="btn btn-primary">Сохранить</button>
                <button name="catalogue-item-accept" id="catalogue-item-accept" type="submit" class="btn btn-success">Применить</button>
            </fieldset>
        </form>
    <?
    }

}