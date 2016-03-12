define(
    's-datagrid',
    ['jquery', 's-notification', 's-confirmation', 'sform'],
    function($, notification, confirmation) {
        "use strict";

        var actions = $('.s-datagrid [name*="-action-delete-"]'),
            groupActions = $('.s-datagrid [name*="-action-group-"]'),
            groupActionsMasterCheckBox = $('.s-datagrid [name*="group-action-all-check"]');

        // Частные операции
        actions.click(function() {
            var self = $(this),
                url = self.attr('href');

            confirmation.setContent('<div class="row"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">' +
                '<img class="img-responsive" src="/public/assets/images/system/modal-dialog/question.svg">' +
                '</div>' +
                '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">Пожалуйста, подтвердите удаление. Внимание! Выбранный элемент будет удалён из системы.</div>' +
                '</div>');

            confirmation.setConfirmCallback(function() {
                confirmation.element.modal('hide');

                $.ajax({
                    type: "get",
                    url: url,
                    success: function(data, status) {
                        var response = $.parseJSON(data);
                        notification.modalByResponse(response, status);

                        if (response.success) {
                            self.closest('tr').remove();
                        }
                    }
                });
            });

            confirmation.element.modal('show');
            return false;
        });

        // Групповые операции
        groupActionsMasterCheckBox.click(function() {
            $(this).closest('form.s-datagrid').find('[name*="-checked-row-flag-"]').prop('checked', $(this).is(':checked'));
        });

        groupActions.click(function() {
            if (groupActionsMasterCheckBox.closest('form.s-datagrid').find('[name*="-checked-row-flag-"]:checked').length == 0) {
                notification.setContent('<div class="row"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">' +
                    '<img class="img-responsive" src="/public/assets/images/system/modal-dialog/attention.svg">' +
                    '</div>' +
                    '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">Групповая операция невозможна, если не выбраны элементы. Отметьте галочками нужные элементы и повторите операцию.</div>' +
                    '</div>');
                notification.element.modal('show');
                return false;
            }

            var self = $(this),
                form = self.closest('form'),
                url = self.attr('formaction'),
                method = self.attr('formmethod'),
                title = self.attr('title');

            confirmation.setContent('<div class="row"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">' +
                '<img class="img-responsive" src="/public/assets/images/system/modal-dialog/question.svg">' +
                '</div>' +
                '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">Пожалуйста, подтвердите групповую операцию (' + title + '). Данная операция затронет все выбранные строки, будьте внимательны!</div>' +
                '</div>');

            confirmation.setConfirmCallback(function() {
                confirmation.element.modal('hide');

                $.ajax({
                    type: method,
                    url: url,
                    data: form.sForm().collectFields(),
                    success: function(data, status) {
                        var response = $.parseJSON(data);
                        notification.modalByResponse(response, status);
                    }
                });
            });

            confirmation.element.modal('show');
            return false;
        });
    }
);