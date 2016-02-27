<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Pages\Models\Page;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends MasterAdminController {

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

        if (!NotificationLog::instance()->hasProblems()) {
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

            NotificationLog::instance()->pushMessage("Страница \"{$oPage->name}\" успешно " . ($pageId == 0 ? 'добавлена' : 'отредактирована') . '.');
            $redirect = '';
            if (Param::post('page-edit-accept', false)->exists()) {
                $redirect = '/admin/modules/pages/';
            } else {
                if ($pageId == 1) {
                    $redirect = "/admin/modules/pages/edit/?pk={$oPage->getPrimaryKey()}";
                }
            }
            $this->response->send($redirect);
        } else {
            $this->response->send();
        }
    }

}