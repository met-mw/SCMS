define(
    'scms.content-to-modal',
    [
        'jquery',
        's-information',
    ],
    function($, information) {
        "use strict";

        $(document).ready(function() {
            $('.content-to-modal').click(function() {
                information.setContent($(this).next().html());
                information.element.modal('show');
            });
        });
    }
);