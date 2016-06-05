<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Pages\Models\Page;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController {

    public function actionIndex() {
        $this->needAuthenticate();

        $pageId = Param::get('id')
            ->noEmpty('Не задан обязательный параметр.')
            ->asInteger(true, 'Параметр должен быть числом.');

        /** @var Page $oPage */
        $oPage = DataSource::factory(Page::cls(), $pageId);
        if (is_null($oPage) || !$oPage->getPrimaryKey()) {
            SCMSNotificationLog::instance()->pushError('Статическая страница не найдена.');
        } else {
            SCMSNotificationLog::instance()->pushMessage("Страница \"$oPage->name\" успешно удалена.");
            $oPage->deleted = true;
            $oPage->commit();
        }

        $this->Response->send();
    }

} 