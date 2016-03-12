define(
    'modal-popup',
    [
        'jquery',
        'text!/public/assets/js/templates/modal-popup/confirmation.html',
        'text!/public/assets/js/templates/modal-popup/notification.html',
        'jquery-tmpl'
    ],
    function($, templateConfirmation, templateNotification) {
        "use strict";

        return {
            createModalAndAppend: function(parent, template) {
                var modal = this.createModal(template);

                modal.element.on('hidden.bs.modal', function (e) {
                    modal.remove();
                });
                modal.element.appendTo(parent);

                return modal;
            },
            createModal: function(template) {
                var result = {
                        element: template,
                        remove: function() {
                            this.element.remove();
                        },
                        isShown: function() {
                            return this.element.is(':visible');
                        },
                        show: function() {
                            this.element.modal('show');
                        },
                        hide: function() {
                            this.element.modal('hide');
                        },
                        modalByResponse: function(response, status) {
                            var body = this.element.find('.modal-body');
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
                                this.element.modal({show: true});
                                this.element.on('hidden.bs.modal', function (e) {
                                    if (response.redirect != '') {
                                        $(location).attr('href', response.redirect);
                                    }
                                    result.element.remove();
                                    return true;
                                });
                            } else {
                                if (response.redirect != '') {
                                    $(location).attr('href', response.redirect);
                                }

                                result.element.remove();
                                return true;
                            }

                            result.element.remove();
                            return false;
                        }
                    };

                return result;
            },
            createNotification: function(parent, message) {
                return this.createModalAndAppend(parent, $(templateNotification).tmpl({message: message}));
            },
            createConfirmation: function(parent, message) {
                return this.createModalAndAppend(parent, $(templateConfirmation).tmpl({message: message}));
            }
        };
    }
);