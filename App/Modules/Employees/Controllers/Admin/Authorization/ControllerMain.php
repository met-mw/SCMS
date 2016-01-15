<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Views\Admin\ViewAuthorization;
use SFramework\Classes\Param;
use SFramework\Classes\Registry;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $error = Param::get('error', false)->asInteger();

        $frame = Registry::frame('back_clear');
        $view = new ViewAuthorization();

        if (!is_null($error)) {
            switch ($error) {
                case 0:
                    $view->errorText = 'Неверный email или пароль.';
                    break;
                default:
                    $view->errorText = 'Произошла неизвестная ошибка.';
            }
        }

        $frame->bindView('content', $view);
        $frame->render();
    }

} 