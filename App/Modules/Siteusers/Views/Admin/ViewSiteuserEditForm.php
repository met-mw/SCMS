<?php
namespace App\Modules\Siteusers\Views\Admin;


use App\Modules\Siteusers\Models\Siteuser;
use SFramework\Classes\View;

class ViewSiteuserEditForm extends View
{

    /** @var Siteuser */
    public $oSiteuser;
    public $backUrl;

    public function __construct()
    {
        $this->optional[] = 'oSiteuser';
    }

    public function currentRender()
    {
        ?>
        <form action="/admin/modules/siteusers/save/" id="siteuser-edit-form" method="post">
            <legend><?= $this->oSiteuser !== null ? 'Редактирование' : 'Добавление' ?> пользователя</legend>
            <fieldset>
                <? if ($this->oSiteuser !== null): ?>
                <input type="hidden" id="siteuser-edit-id" name="siteuser-edit-id" value="<?= $this->oSiteuser->getPrimaryKey() ?>" />
                <? endif; ?>

                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="siteuser-edit-surname">Фамилия</label>
                                    <input type="text" class="form-control" id="siteuser-edit-surname" name="siteuser-edit-surname" placeholder="Фамилия" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->surname : '' ?>" required="required" />
                                    <span class="help-block">Фамилия пользователя</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="siteuser-edit-name">Имя</label>
                                    <input type="text" class="form-control" id="siteuser-edit-name" name="siteuser-edit-name" placeholder="Имя" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->name : '' ?>" required="required" />
                                    <span class="help-block">Имя пользователя</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="siteuser-edit-patronymic">Отчество</label>
                                    <input type="text" class="form-control" id="siteuser-edit-patronymic" name="siteuser-edit-patronymic" placeholder="Отчество" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->patronymic : '' ?>" required="required" />
                                    <span class="help-block">Отчество пользователя</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="siteuser-edit-email">E-mail</label>
                                    <input type="email" class="form-control" id="siteuser-edit-email" name="siteuser-edit-email" placeholder="E-mail" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->email : '' ?>" required="required" />
                                    <span class="help-block">Адрес электронной почты пользователя</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="siteuser-edit-phone">Телефон</label>
                                    <input type="text" class="form-control" id="siteuser-edit-phone" name="siteuser-edit-phone" placeholder="Телефон" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->phone : '' ?>" required="required" />
                                    <span class="help-block">Номер контактного телефона пользователя</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="siteuser-edit-postcode">Индекс</label>
                                    <input type="text" class="form-control" id="siteuser-edit-postcode" name="siteuser-edit-postcode" placeholder="Индекс" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->postcode : '' ?>" required="required" />
                                    <span class="help-block">Почтовый индекс пользователя</span>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10">
                                <div class="form-group">
                                    <label for="siteuser-edit-address">Адрес</label>
                                    <input type="text" class="form-control" id="siteuser-edit-address" name="siteuser-edit-address" placeholder="Адрес" value="<?= !is_null($this->oSiteuser) ? $this->oSiteuser->mail_address : '' ?>" required="required" />
                                    <span class="help-block">Фактический адрес пребывания пользователя</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="siteuser-edit-type">Тип</label>
                                    <select class="form-control" name="siteuser-edit-type" id="siteuser-edit-type" required="required">
                                        <option value="">Не выбрано</option>
                                        <option value="<?= Siteuser::TYPE_USER ?>"<?= $this->oSiteuser && $this->oSiteuser->type == Siteuser::TYPE_USER ? ' selected="selected"' : '' ?>>Пользователь</option>
                                        <option value="<?= Siteuser::TYPE_CONTRACTOR ?>"<?= $this->oSiteuser && $this->oSiteuser->type == Siteuser::TYPE_CONTRACTOR ? ' selected="selected"' : '' ?>>Контрагент</option>
                                    </select>
                                    <span class="help-block">Тип пользователя</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="siteuser-edit-status">Статус</label>
                                    <select class="form-control" name="siteuser-edit-status" id="siteuser-edit-status" required="required">
                                        <option value="">Не выбрано</option>
                                        <option value="<?= Siteuser::STATUS_UNCONFIRMED ?>"<?= $this->oSiteuser && $this->oSiteuser->status == Siteuser::STATUS_UNCONFIRMED ? ' selected="selected"' : '' ?>>Не подтверждён</option>
                                        <option value="<?= Siteuser::STATUS_CONFIRMED ?>"<?= $this->oSiteuser && $this->oSiteuser->status == Siteuser::STATUS_CONFIRMED ? ' selected="selected"' : '' ?>>Подтверждён</option>
                                        <option value="<?= Siteuser::STATUS_DENIED ?>"<?= $this->oSiteuser && $this->oSiteuser->status == Siteuser::STATUS_DENIED ? ' selected="selected"' : '' ?>>Отказался</option>
                                    </select>
                                    <span class="help-block">Статус пользователя</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="siteuser-edit-active">Активность</label>
                                    <input class="checkbox" name="siteuser-edit-active" id="siteuser-edit-active" title="Активен-ли пользователь." type="checkbox"<?= (is_null($this->oSiteuser) || $this->oSiteuser->active  ? ' CHECKED' : '') ?>>
                                    <span class="help-block">Активен-ли пользователь</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <hr/>
            <a href="<?= $this->backUrl ?>" class="btn btn-warning">Отмена</a>
            <button id="siteuser-edit-save" name="siteuser-edit-save" type="submit" class="btn btn-primary">Сохранить</button>
            <button id="siteuser-edit-accept" name="siteuser-edit-accept" type="submit" class="btn btn-success">Применить</button>
        </form>
        <?
    }
}