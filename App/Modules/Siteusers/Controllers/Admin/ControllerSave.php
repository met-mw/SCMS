<?php
namespace App\Modules\Siteusers\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Siteusers\Classes\Authorizator;
use App\Modules\Siteusers\Models\Siteuser;
use Exception;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends MasterAdminController
{

    public function actionIndex()
    {
        if (CoreFunctions::isAJAX() && !$this->employeeAuthorizator->authorized()) {
            NotificationLog::instance()->pushError('Нет доступа!');
            $this->response->send();

            return;
        }

        $this->authorizeIfNot();
        $siteuserAuthorizator = new Authorizator();

        $siteuserId = Param::post('siteuser-edit-id', false)->asInteger(false);
        $name = Param::post('siteuser-edit-name')->noEmpty('Заполните поле "Имя"')->asString();
        $surname = Param::post('siteuser-edit-surname')->noEmpty('Заполните поле "Фамилия"')->asString();
        $patronymic = Param::post('siteuser-edit-patronymic')->noEmpty('Заполните поле "Отчество"')->asString();
        $email = Param::post('siteuser-edit-email')->noEmpty('Заполните поле "E-mail"')->asEmail(true, 'Вы ввели некорректный email.');
        $phone = Param::post('siteuser-edit-phone')->noEmpty('Заполните поле "Телефон"')->asString();
        $postcode = Param::post('siteuser-edit-postcode')->noEmpty('Заполните поле "Индекс"')->asString();
        $address = Param::post('siteuser-edit-address', false)->noEmpty('Заполните поле "Адрес"')->asString();
        $type = Param::post('siteuser-edit-type', false)->noEmpty('Необходимо указать тип пользователя')->asInteger(true, 'Недопустимое значение поля "Тип"');
        $status = Param::post('siteuser-edit-status', false)->noEmpty('Необходимо указать статус пользователя')->asInteger(true, 'Недопустимое значение поля "Статус"');
        $active = (bool)Param::post('siteuser-edit-active')->exists();

        $accept = Param::post('siteuser-edit-accept', false);

        if (!in_array($type, [Siteuser::TYPE_USER, Siteuser::TYPE_CONTRACTOR])) {
            NotificationLog::instance()->pushError('Недопустимое значение поля "Тип".');
        }
        if (!in_array($status, [Siteuser::STATUS_UNCONFIRMED, Siteuser::STATUS_CONFIRMED, Siteuser::STATUS_DENIED])) {
            NotificationLog::instance()->pushError('Недопустимое значение поля "Статус".');
        }

        $oSiteusers = DataSource::factory(Siteuser::cls());
        $oSiteusers->builder()
            ->where("deleted=0")
            ->whereAnd()
            ->whereBracketOpen()
            ->where("email='{$email}'")
            ->whereOr()
            ->where("phone='{$phone}'")
            ->whereBracketClose();
        /** @var Siteuser[] $aSiteusers */
        $aSiteusers = $oSiteusers->findAll();
        if (!empty($aSiteusers)) {
            $oSiteuser = $aSiteusers[0];
            if ($oSiteuser->email == $email) {
                NotificationLog::instance()->pushError('Пользователь с таким Email уже зарегистрирован в системе.');
            }
            if ($oSiteuser->phone == $phone) {
                NotificationLog::instance()->pushError('Пользователь с таким телефоном уже зарегистрирован в системе.');
            }
        }

        if (CoreFunctions::isAJAX() && NotificationLog::instance()->hasProblems()) {
            $this->response->send();
            return;
        }

        /** @var Siteuser $oSiteuser */
        $oSiteuser = DataSource::factory(Siteuser::cls(), $siteuserId);
        $oSiteuser->name = $name;
        $oSiteuser->surname = $surname;
        $oSiteuser->patronymic = $patronymic;
        $oSiteuser->email = $email;
        $oSiteuser->phone = $phone;
        $oSiteuser->postcode = $postcode;
        $oSiteuser->mail_address = $address;
        $oSiteuser->password = $siteuserAuthorizator->defaultPassword();
        $oSiteuser->type = $type;
        $oSiteuser->status = $status;
        $oSiteuser->active = $active;
        if ($oSiteuser->isNew()) {
            $oSiteuser->deleted = false;
        }
        try {
            $oSiteuser->commit();
        } catch (Exception $e) {
            NotificationLog::instance()->pushError($e->getMessage());
        }

        $redirect = '';
        if (!NotificationLog::instance()->hasProblems()) {
            NotificationLog::instance()->pushMessage("Пользователь \"{$oSiteuser->email}\" успешно " . ($siteuserId == 0 ? 'добавлен' : 'отредактирован') . ".");
            $redirect = "/admin/modules/siteusers/edit/?id={$oSiteuser->getPrimaryKey()}";
            if ($accept->exists()) {
                $redirect = '/admin/modules/siteusers/';
            }
        }

        $this->response->send($redirect);
    }

}