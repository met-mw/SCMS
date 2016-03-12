<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewNotificationsModal extends View {

    public function currentRender() {
        ?>
        <div id="modal-notification" class="modal fade" tabindex="-1" role="dialog" data-show="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">SCMS Система оповещений. Внимание!</h4>
                    </div>
                    <div class="modal-body bg-info"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <?
    }

}