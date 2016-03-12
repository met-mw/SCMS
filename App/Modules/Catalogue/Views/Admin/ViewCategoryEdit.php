<?php
namespace App\Modules\Catalogue\Views\Admin;


use App\Models\Module;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\View;

class ViewCategoryEdit extends View {

    /** @var int */
    public $parentId;
    /** @var Category */
    public $category;
    /** @var ViewNotificationsModal */
    public $notificationsView;

    public function __construct() {
        $this->notificationsView = new ViewNotificationsModal();

        $this->optional[] = 'parentId';
    }

    public function currentRender() {
        ?>
            Привет!
        <?
        $this->notificationsView->render();
    }

}