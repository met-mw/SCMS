<?php
namespace App\Modules\Employees\Controllers\Admin\Authorization;


use App\Classes\AdministratorAreaController;
use App\Modules\Employees\Views\Admin\ViewAuthorization;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewInformationModal;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\Param;
use SFramework\Classes\Registry;

class ControllerMain extends AdministratorAreaController {

    public function actionIndex() {
        $error = Param::get('error', false)->asInteger(false);

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

        $frame->bindView('modal-notification', new ViewNotificationsModal());
        $frame->bindView('modal-confirmation', new ViewConfirmationModal());
        $frame->bindView('modal-information', new ViewInformationModal());
        $frame->bindView('content', $view);
        $frame->render();
    }

} 