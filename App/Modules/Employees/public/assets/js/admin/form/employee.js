define(
    'module.employees.edit',
    [
        'jquery',
        's-notification',
        'sform',
        'sajaxloader'
    ],
    function($, notification) {
        "use strict";

        $(document).ready(function() {
            $('#employee-save, #employee-accept').click(function() {
                var self = $(this);
                var form = $('#employee-form');
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