define(
    'module.gallery.init',
    [
        'jquery',
        'jquery-fancybox',
        'jquery-galleria',
        'jquery-lightbox'
    ],
    function($) {
        "use strict";

        $(document).ready(function() {
            $("a.fancyimage").fancybox();

            if ($('.galleria').length > 0) {
                Galleria.loadTheme('/public/assets/js/bower_components/jquery-galleria/src/themes/classic/galleria.classic.js');

                Galleria.run('.galleria', {
                    extend: function(){
                        this.bind('image', function(e) {
                            jQuery(e.imageTarget).click(this.proxy(function() {
                                this.openLightbox();
                            }));
                        });
                    }
                });
            }
        });
    }
);