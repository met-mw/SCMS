define(
    's-information',
    [
        'jquery'
    ],
    function($) {
        "use strict";

        return {
            element: $('#modal-information'),
            modalIsShown: function() {
                return this.element.is(':visible');
            },
            setContent: function(content) {
                $('.modal-body', this.element).html(content);
            }
        };
    }
);