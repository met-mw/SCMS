define(
    'module.employee.registration',
    [
        'jquery',
        'modal',
        'sform',
        'sajaxloader'
    ],
    function($, modal) {
        "use strict";

        $(document).ready(function() {
            $('#employee-registration-form-sign-up').click(function() {
                var self = $(this);
                var form = $('#employee-registration-form');
                var action = form.attr('action');
                var params = form.sForm().collectFields();
                params[self.attr('name')] = self.val();

                var elementsForDisable = form.find('input:enabled, select:enabled, textarea:enabled, button:enabled');
                elementsForDisable.prop('disabled', true);
                var loader = form.sAjaxLoader();
                loader.show();

                $.post(
                    action,
                    params,
                    function(data, status) {
                        var response = $.parseJSON(data);
                        modal.modalByResponse(response, status);
                        elementsForDisable.prop('disabled', false);
                        loader.hide();
                    }
                );

                return false;
            });
        });
    }
);