<?php
namespace App\Modules\Frames\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Frames\Views\Admin\ViewEditForm;
use App\Views\Admin\ViewResponse;
use SFramework\Classes\Param;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $frame = $this->frame;
        $frame->addJs('/public/bower_components/markitup/markitup/jquery.markitup.js');
        $frame->addJs('/public/bower_components/markitup/markitup/sets/default/set.js');
        $frame->addCss('/public/bower_components/markitup/markitup/skins/markitup/style.css');
        $frame->addCss('/public/bower_components/markitup/markitup/sets/default/style.css');

        $frameName = Param::get('name', false)->asString();
        $view = new ViewEditForm();
        $view->response = new ViewResponse($this->alertClass, $this->alertHeader, $this->alertText);
        $view->frameName = $frameName;

        $frame->bindView('content', $view);
        $frame->render();
    }

} 