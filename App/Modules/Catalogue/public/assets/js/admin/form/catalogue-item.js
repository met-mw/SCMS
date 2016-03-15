define(
    'module.catalogue.item.edit',
    [
        'jquery',
        's-notification',
        'sform',
        'sajaxloader',
        'jquery-fancybox',
        'jquery-textchange',
    ],
    function($, notification) {
        "use strict";

        $(document).ready(function() {
            var thumbnailPath = $('#catalogue-item-thumbnail'),
                thumbnailImg = $('#catalogue-item-thumbnail-img'),
                thumbnailA = $('#catalogue-item-thumbnail-a'),
                thumbnailRemoveButton = $('#catalogue-item-thumbnail-remove-btn');

            $('.catalogue-item-thumbnail-btn').fancybox({
                width: 900,
                height: 600,
                type: 'iframe',
                fitToView: false,
                autoSize: false,
                afterClose: function() {
                    thumbnailPath.trigger('change');
                }
            });

            thumbnailImg.fancybox({
                fitToView: true,
                autoSize: false,
                afterClose: function() {
                    thumbnailImg.show();
                }
            });

            thumbnailRemoveButton.click(function() {
                thumbnailPath.val('/public/assets/images/system/no-image.svg');
                thumbnailPath.trigger('change');
            });

            thumbnailPath.change(function() {
                thumbnailImg.attr('src', $(this).val());
                thumbnailA.attr('href', $(this).val());
            });

            $('#catalogue-item-accept, #catalogue-item-save').click(function() {
                tinyMCE.triggerSave();

                var self = $(this);
                var form = $('#catalogue-item-edit-form');
                var action = form.attr('action');
                var params = form.sForm().collectFields();
                params[self.attr('name')] = self.val();

                var elementsForDisable = form.find('input:enabled, select:enabled, textarea:enabled, button:enabled');
                elementsForDisable.prop('disabled', true);
                var loader = self.parent().sAjaxLoader();
                loader.show();
                $.post(
                    action,
                    params,
                    function(data, status) {
                        var response = $.parseJSON(data);
                        elementsForDisable.prop('disabled', false);
                        loader.hide();
                        notification.modalByResponse(response, status);
                    }
                );
                return false;
            });
        });
    }
);