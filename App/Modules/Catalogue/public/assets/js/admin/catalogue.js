define(
    'module.catalogue',
    [
        'jquery',
        'jquery-fancybox'
    ],
    function($) {
        "use strict";

        $(document).ready(function() {
            $('a.fancybox').fancybox({
                fitToView: true,
                autoSize: false,
                afterClose: function() {
                    $('img', this).show();
                }
            });
        });
    }
);