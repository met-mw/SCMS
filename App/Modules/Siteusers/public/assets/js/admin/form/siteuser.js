define(
    'module.siteuser.edit',
    [
        'jquery',
        's-notification',
        'sform',
        'sajaxloader'
    ],
    function($, notification) {
        "use strict";

        $(document).ready(function() {
            $('#siteuser-edit-accept, #siteuser-edit-save').click(function() {
                $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
                $(this).attr("clicked", "true");
            });

            $('#siteuser-edit-form').submit(function() {
                var clickedButton = $("button[type=submit][clicked=true]");
                var form = $(this);
                var action = form.attr('action');
                var params = form.sForm().collectFields();
                params[clickedButton.attr('name')] = clickedButton.val();

                var elementsForDisable = form.find('input:enabled, select:enabled, textarea:enabled, button:enabled');
                elementsForDisable.prop('disabled', true);
                var loader = clickedButton.parent().sAjaxLoader();
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