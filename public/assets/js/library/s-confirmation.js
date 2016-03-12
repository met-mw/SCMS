define(
    's-confirmation',
    [
        'jquery'
    ],
    function($) {
        "use strict";

        return {
            element: $('#modal-confirmation'),
            modalIsShown: function() {
                return this.element.is(':visible');
            },
            setContent: function(content) {
                $('.modal-body', this.element).html(content);
            },
            setConfirmCallback: function(callback) {
                var buttonConfirm = $('.modal-confirmation-confirm', this.element);

                buttonConfirm.unbind('click');
                buttonConfirm.bind('click', callback);
            }
        };
    }
);