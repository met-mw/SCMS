define(
    'module.structure.edit',
    [
        'jquery',
        'modal',
        'sform',
        'sajaxloader'
    ],
    function($, modal) {
        "use strict";

        $(document).ready(function() {
            $('#structure-accept, #structure-save').click(function() {
                var self = $(this);
                var form = $('#structure-edit-form');
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
                        modal.modalByResponse(response, status);
                        elementsForDisable.prop('disabled', false);
                        loader.hide();
                    }
                );
                return false;
            });

            $('#structure-module').change(function() {
                $.get(
                    "/admin/modules/structures/edit/ajaxmoduleconfig",
                    {
                        structure_id: $('#structure-id').val(),
                        module_id: $('#structure-module').val()
                    },
                    function(data, status) {
                        var response = $.parseJSON(data);
                        modal.modalByResponse(response, status);
                        $('#module-controls-container').html(response.additional_data.form);
                    }
                );
            });
        });
    }
);