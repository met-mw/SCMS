<?php
namespace App\Modules\Employees\Views\Admin;


use App\Modules\Employees\Views\Admin\Forms\ViewAuthorization as ViewFormAuthorization;
use App\Views\Admin\ViewNotifications;
use SFramework\Classes\View;

class ViewAuthorization extends View {

    /** @var ViewFormAuthorization */
    public $form;
    public $errorText;
    /** @var ViewNotifications */
    public $notificationsView;

    public function __construct() {
        $this->optional = ['errorText'];
        $this->form = new ViewFormAuthorization();
        $this->notificationsView = new ViewNotifications();
    }

    public function currentRender() {
        ?>
        <div>
            <div class="page-header" style="margin-left: 20px; margin-right: 20px;">
                <h1>Панель управления SCMS.</h1>
                <blockquote>
                    <p class="text-primary">При возникновении проблем с доступом обратитесь к администратору сайта.</p>
                    <footer>С уважением, <a href="http://sproject.ru" target="_blank" <cite title="SProject">SProject</cite></a></footer>
                </blockquote>
            </div>
            <? if ($this->errorText): ?>
                <div class="alert alert-danger center-block" role="alert" style="width: 500px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4>Ошибка авторизации!</h4>
                    <p><?= $this->errorText ?></p>
                </div>
            <? endif; ?>
            <? $this->form->render(); ?>
        </div>
        <?
        $this->notificationsView->render();
    }

}