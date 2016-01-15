define(
    'modal',
    [
        'jquery'
    ],
    function($) {
        "use strict";

        return {
            modalIsShown: function() {
                return $('#message-dialog').is(':visible');
            },
            modalByResponse: function(response, status) {
                var dialog = $('#message-dialog');
                var body = dialog.find('.modal-body');
                body.html('');

                if (status) {
                    if (response.success) {
                        $(response.messages).each(function() {
                            body.html(body.html() + '<p class="alert alert-success">' + this + '</p>');
                        });
                    } else {
                        $(response.errors).each(function() {
                            body.html(body.html() + '<p class="alert alert-danger">' + this + '</p>');
                        });

                        $(response.warnings).each(function() {
                            body.html(body.html() + '<p class="alert alert-warning">' + this + '</p>');
                        });

                        $(response.notices).each(function() {
                            body.html(body.html() + '<p class="alert alert-info">' + this + '</p>');
                        });
                    }
                } else {
                    body.html(body.html() + '<p class="alert alert-danger">Произошла неизвестная ошибка.</p>');
                }

                if (body.html() != '') {
                    dialog.modal({show: true});
                    dialog.on('hidden.bs.modal', function (e) {
                        if (response.redirect != '') {
                            $(location).attr('href', response.redirect);
                        }

                        return true;
                    });
                } else {
                    if (response.redirect != '') {
                        $(location).attr('href', response.redirect);
                    }

                    return true;
                }

                return false;
            }
        };
    }
);