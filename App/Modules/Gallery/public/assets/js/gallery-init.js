define(
    'module.gallery.init',
    [
        'jquery',
        'jquery-fancybox',
        'jquery-galleria'
    ],
    function($) {
        "use strict";

        $(document).ready(function() {
            $(document).ready(function() {
                $("a.fancyimage").fancybox();
            });

            if ($('.galleria').length > 0) {
                Galleria.loadTheme('/public/assets/js/bower_components/jquery-galleria/src/themes/classic/galleria.classic.js');
                Galleria.configure({
                    lightbox: true
                });
                Galleria.run('.galleria', {});
            }
        });
    }
);