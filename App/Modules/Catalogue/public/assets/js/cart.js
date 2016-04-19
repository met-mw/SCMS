define(
    'module.catalogue.cart',
    [
        'jquery',
        's-notification',
        'sajaxloader'
    ],
    function($, notification) {
        "use strict";

        $(document).ready(function() {
            var body = $('body'),
                addToCartLink = $('.catalogue-add-to-cart');

            body.on('hidden.bs.modal', '#modal-catalogue-cart', function () {
                var self = $(this),
                    dataRows = $('.cart-data-row', self),
                    items = {};

                dataRows.each(function(index, element) {
                    items[$('input[name="id"]', element).val()] = $('input.catalogue-item-count', element).val();
                });

                $.get(
                    '/catalogue?action=set-cart-items',
                    {
                        items: items
                    },
                    function(data, status) {
                        var response = $.parseJSON(data);
                        $('#catalogue-cart-count').text(response.additional_data.totalCount);
                        self.removeData('bs.modal');
                        notification.modalByResponse(response, status);
                    }
                );

            });

            addToCartLink.click(function() {
                var self = $(this);

                var loader = self.parent().sAjaxLoader();
                loader.show();
                $.get(
                    self.attr('href'),
                    {
                        count: self.closest('.input-group').find('input').val()
                    },
                    function(data, status) {
                        var response = $.parseJSON(data);
                        $('#catalogue-cart-count').text(response.additional_data.totalCount);
                        loader.hide();
                        notification.modalByResponse(response, status);
                    }
                );
                return false;
            });

        });
    }
);