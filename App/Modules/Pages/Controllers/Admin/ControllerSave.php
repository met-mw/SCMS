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

        $pageId = Param::post('page-id', false)->asInteger(false);

        $name = Param::post('page-name')->noEmpty('Поле "Наименование" должно быть заполнено.')->asString();
        $description = Param::post('page-description')->asString();
        $content = Param::post('page-content')->asString();

        if (!NotificationLog::instance()->hasProblems()) {
            /** @var Page $page */
            $page = DataSource::factory(Page::cls(), $pageId == 0 ? null : $pageId);
            $page->name = $name;
            $page->description = $description;
            $page->content = $content;
            $page->commit();
        } else {
            $this->response->send();
            exit;
        }

        $redirect = '';
        if (!NotificationLog::instance()->hasProblems()) {
            NotificationLog::instance()->pushMessage("Страница \"{$page->name}\" успешно " . ($pageId == 0 ? 'добавлена' : 'отредактирована') . '.');
            if (Param::post('page-accept', false)->exists()) {
                $redirect = '/admin/modules/pages/';
            } else {
                if ($pageId == 0) {
                    $redirect = "/admin/modules/pages/edit/?pk={$page->getPrimaryKey()}";
                }
            }
        }

        $this->response->send($redirect);
    }

}