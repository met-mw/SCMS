define(
    'entity',
    ['jquery', 'modal'],
    function($, modal) {
        "use strict";

        $('.entity-delete').click(function() {
            var self = $(this);
            var href = $(this).closest('a').attr('href');
            var modalConfirm = $('#modal-confirm');
            modalConfirm.find('.modal-body').html("<p>Пожалуйста, подтвердите удаление. Осторожно, восстановить удаляемые данные будет невозможно!</p>");
            $('#dataConfirmOK').click(function() {
                modalConfirm.modal('hide');
                $.get(
                    href,
                    {},
                    function(data, status) {
                        var response = $.parseJSON(data);
                        modal.modalByResponse(response, status);

                        if (response.success) {
                            self.closest('tr').remove();
                        }
                    }
                );
            });
            modalConfirm.modal({show:true});
            return false;
        });
    }
);

