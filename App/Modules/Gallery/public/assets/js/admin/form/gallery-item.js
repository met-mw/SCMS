define(
    'module.gallery.item.edit',
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
            var galleryItemPath = $('#gallery-item-edit-path'),
                galleryItemImg = $('#gallery-item-edit-path-img'),
                galleryItemA = $('#gallery-item-edit-path-a'),
                galleryItemRemoveButton = $('#gallery-item-edit-path-remove-btn');

            $('.gallery-item-edit-path-btn').fancybox({
                width: 900,
                height: 600,
                type: 'iframe',
                fitToView: false,
                autoSize: false,
                afterClose: function() {
                    galleryItemPath.trigger('change');
                }
            });

            galleryItemImg.fancybox({
                fitToView: true,
                autoSize: false,
                afterClose: function() {
                    galleryItemImg.show();
                }
            });

            galleryItemRemoveButton.click(function() {
                galleryItemPath.val('/public/assets/images/system/no-image.svg');
                galleryItemPath.trigger('change');
            });

            galleryItemPath.change(function() {
                galleryItemImg.attr('src', $(this).val());
                galleryItemA.attr('href', $(this).val());
            });

            $('#gallery-item-edit-save, #gallery-item-edit-accept').click(function() {
                var self = $(this);
                var form = $('#gallery-item-edit-form');
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
                        notification.modalByResponse(response, status);
                        elementsForDisable.prop('disabled', false);
                        loader.hide();
                    }
                );

                return false;
            });
        });
    }
);