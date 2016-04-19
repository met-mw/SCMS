<?php
namespace App\Modules\Catalogue\Views;


use SFramework\Classes\View;

class ViewCartModal extends View
{

    public function currentRender()
    {
        ?>
        <div id="modal-catalogue-cart" class="modal" tabindex="-1" role="dialog" data-show="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Моя корзина</h4>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
        <?
    }

}