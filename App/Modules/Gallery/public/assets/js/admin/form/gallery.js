define(
    'module.gallery.edit',
    [
        'jquery',
        's-notification',
        'sform',
        'sajaxloader',
        'jquery-fancybox'
    ],
    function($, notification) {
        "use strict";

        $(document).ready(function() {
            $('#gallery-edit-save, #gallery-edit-accept').click(function() {
                var self = $(this);
                var form = $('#gallery-edit-form');
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