define(
    'module.catalogue',
    [
        'jquery',
        's-information',
        'jquery-fancybox'
    ],
    function($, information) {
        "use strict";

        $(document).ready(function() {
            $('.cut-string-display-to-modal').click(function() {
                information.setContent($(this).next().html());
                information.element.modal('show');
            });

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