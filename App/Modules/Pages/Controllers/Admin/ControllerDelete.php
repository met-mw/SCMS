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
        $pageId = Param::get('pk')->asInteger();
        /** @var Page $page */
        $page = DataSource::factory(Page::cls(), $pageId);
        NotificationLog::instance()->pushMessage("Страница \"$page->name\" успешно удалена.");
        $page->delete();

        $this->response->send();
    }

} 