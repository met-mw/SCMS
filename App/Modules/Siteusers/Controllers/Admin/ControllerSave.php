<?php
namespace App\Modules\Siteusers\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Siteusers\Classes\Authorizator;
use App\Modules\Siteusers\Models\Siteuser;
use Exception;
use SFramework\Classes\CoreFunctions;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends AdministratorAreaController
{

    public function actionIndex()
    {
        if (CoreFunctions::isAJAX() && !$this->EmployeeAuthorizator->authorized()) {
            SCMSNotificationLog::instance()->pushError('Нет доступа!');
            $this->Response->send();

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
            SCMSNotificationLog::instance()->pushError('Недопустимое значение поля "Тип".');
        }
        if (!in_array($status, [Siteuser::STATUS_UNCONFIRMED, Siteuser::STATUS_CONFIRMED, Siteuser::STATUS_DENIED])) {
            SCMSNotificationLog::instance()->pushError('Недопустимое значение поля "Статус".');
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
                SCMSNotificationLog::instance()->pushError('Пользователь с таким Email уже зарегистрирован в системе.');
            }
            if ($oSiteuser->phone == $phone) {
                SCMSNotificationLog::instance()->pushError('Пользователь с таким телефоном уже зарегистрирован в системе.');
            }
        }

        if (CoreFunctions::isAJAX() && SCMSNotificationLog::instance()->hasProblems()) {
            $this->Response->send();
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
            SCMSNotificationLog::instance()->pushError($e->getMessage());
        }

        $redirect = '';
        if (!SCMSNotificationLog::instance()->hasProblems()) {
            SCMSNotificationLog::instance()->pushMessage("Пользователь \"{$oSiteuser->email}\" успешно " . ($siteuserId == 0 ? 'добавлен' : 'отредактирован') . ".");
            $redirect = "/admin/modules/siteusers/edit/?id={$oSiteuser->getPrimaryKey()}";
            if ($accept->exists()) {
                $redirect = '/admin/modules/siteusers/';
            }
        }

        $this->Response->send($redirect);
    }

}