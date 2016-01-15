<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewConfirmModal extends View {

    public function currentRender() {
        ?>
        <div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Подтверждение</h4>
                    </div>
                    <div class="modal-body alert-warning"></div>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="dataConfirmOK">Подтверждаю</button>
                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
        <?
    }

}