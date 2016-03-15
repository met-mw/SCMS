<?php


namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewInformationModal extends View {

    public function currentRender() {
        ?>
        <div id="modal-information" class="modal" tabindex="-1" role="dialog" data-show="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Информационное окно.</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Хорошо</button>
                    </div>
                </div>
            </div>
        </div>
        <?
    }
}