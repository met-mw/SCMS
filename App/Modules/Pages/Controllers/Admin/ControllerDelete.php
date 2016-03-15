<?php
namespace App\Modules\Pages\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Pages\Models\Page;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $pageId = Param::get('id')
            ->noEmpty('Не задан обязательный параметр.')
            ->asInteger(true, 'Параметр должен быть числом.');

        /** @var Page $oPage */
        $oPage = DataSource::factory(Page::cls(), $pageId);
        if (is_null($oPage) || !$oPage->getPrimaryKey()) {
            NotificationLog::instance()->pushError('Статическая страница не найдена.');
        } else {
            NotificationLog::instance()->pushMessage("Страница \"$oPage->name\" успешно удалена.");
            $oPage->deleted = true;
            $oPage->commit();
        }

        $this->response->send();
    }

} 