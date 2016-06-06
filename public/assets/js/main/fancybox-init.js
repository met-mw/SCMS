define(
    'fancybox-init',
    [
        'jquery',
        'jquery-fancybox'
    ],
    function($) {
        "use strict";

        $(document).ready(function() {
            $("a.fancybox").fancybox();
        });
    }
);