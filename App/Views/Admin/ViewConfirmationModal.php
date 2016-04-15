<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewConfirmationModal extends View {

    public function currentRender() {
        ?>
        <div id="modal-confirmation" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Подтверждение</h4>
                    </div>
                    <div class="modal-body bg-warning"></div>
                    <div class="modal-footer">
                        <button class="btn btn-danger modal-confirmation-confirm"><span class="glyphicon glyphicon-ok"></span> Подтверждаю</button>
                        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span> Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        <?
    }

}