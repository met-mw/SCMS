<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Pages\Models\Page;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageId = Param::post('page-edit-id', false)->asInteger(false);

        $name = Param::post('page-edit-name')
            ->noEmpty('Поле "Наименование" должно быть заполнено.')
            ->asString();
        $description = Param::post('page-edit-description')
            ->asString();
        $content = Param::post('page-edit-content')
            ->asString();
        $active = (bool)Param::post('page-edit-active')
            ->exists();

        if (!SCMSNotificationLog::instance()->hasProblems()) {
            /** @var Page $oPage */
            $oPage = DataSource::factory(Page::cls(), $pageId == 0 ? null : $pageId);
            $oPage->name = $name;
            $oPage->description = $description;
            $oPage->content = $content;
            $oPage->active = $active;
            if (!$oPage->getPrimaryKey()) {
                $oPage->deleted = false;
            }

            $oPage->commit();

            SCMSNotificationLog::instance()->pushMessage("Страница \"{$oPage->name}\" успешно " . ($pageId == 0 ? 'добавлена' : 'отредактирована') . '.');
            $redirect = '';
            if (Param::post('page-edit-accept', false)->exists()) {
                $redirect = '/admin/modules/pages/';
            } elseif ($pageId == 1) {
                $redirect = "/admin/modules/pages/edit/?id={$oPage->getPrimaryKey()}";
            }
            $this->Response->send($redirect);
        } else {
            $this->Response->send();
        }
    }

}