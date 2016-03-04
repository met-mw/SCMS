<?php
namespace App\Modules\Catalogue\Views\Admin;


use App\Models\Module;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\ViewNotifications;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\View;

class ViewCatalogue extends View {

    /** @var int */
    public $parentId;
    /** @var ViewNotifications */
    public $notificationsView;

    public function __construct() {
        $this->notificationsView = new ViewNotifications();
    }

    public function currentRender() {
        ?>

        <?
        $this->notificationsView->render();
    }

}