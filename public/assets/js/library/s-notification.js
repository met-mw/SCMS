define(
    's-notification',
    [
        'jquery'
    ],
    function($) {
        "use strict";

        return {
            element: $('#modal-notification'),
            modalIsShown: function() {
                return this.element.is(':visible');
            },
            setContent: function(content) {
                $('.modal-body', this.element).html(content);
            },
            modalByResponse: function(response, status) {
                var body = $('.modal-body', this.element),
                    content = '';
                body.html('');

                if (status) {
                    if (response.success) {
                        $(response.messages).each(function() {
                            content += '<p>' + this + '</p>';
                        });
                    } else {
                        $(response.errors).each(function() {
                            content += '<p class="alert alert-danger">' + this + '</p>';
                        });

                        $(response.warnings).each(function() {
                            content += '<p class="alert alert-warning">' + this + '</p>';
                        });

                        $(response.notices).each(function() {
                            content += '<p class="alert alert-info">' + this + '</p>';
                        });
                    }
                } else {
                    content += '<p class="alert alert-danger">Произошла неизвестная ошибка.</p>';
                }

                if (content != '') {
                    this.setContent('<div class="row"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">' +
                        '<img class="img-responsive" src="/public/assets/images/system/modal-dialog/attention.svg">' +
                        '</div>' +
                        '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">' + content + '</div>' +
                        '</div>');
                    this.element.modal('show');
                    this.element.on('hidden.bs.modal', function (e) {
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