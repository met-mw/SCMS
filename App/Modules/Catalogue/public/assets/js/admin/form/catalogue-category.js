define(
    'module.catalogue.category.edit',
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
            var thumbnailPath = $('#catalogue-category-thumbnail'),
                thumbnailImg = $('#catalogue-category-thumbnail-img'),
                thumbnailA = $('#catalogue-category-thumbnail-a'),
                thumbnailRemoveButton = $('#catalogue-category-thumbnail-remove-btn');

            $('.catalogue-category-thumbnail-btn').fancybox({
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

            $('#catalogue-category-accept, #catalogue-category-save').click(function() {
                var self = $(this);
                var form = $('#catalogue-category-edit-form');
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