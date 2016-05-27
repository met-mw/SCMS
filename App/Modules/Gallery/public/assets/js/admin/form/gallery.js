define(
    'module.gallery.edit',
    [
        'jquery',
        's-notification',
        'jquery-galleria',
        'sform',
        'sajaxloader'
    ],
    function($, notification) {
        "use strict";
        //var jQueryGalleria = jQuery.noConflict(true);
        //
        //(function($) {
        //    Galleria.loadTheme('/public/assets/js/bower_components/jquery-galleria/src/themes/classic/galleria.classic.js');
        //    Galleria.run('.galleria');
        //})( jQueryGalleria );

        $(document).ready(function() {
            Galleria.loadTheme('/public/assets/js/bower_components/jquery-galleria/src/themes/classic/galleria.classic.js');
            Galleria.run('.galleria');

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