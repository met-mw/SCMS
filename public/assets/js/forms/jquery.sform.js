(function($){
    $.fn.sForm = function() {
//        var form = this;

        return {
            form: this,
            collectFields: function(withDisabled) {
                withDisabled = withDisabled || false;

                var result = {};
                var enabled = withDisabled ? ':enabled' : '';
                var elements = this.form.find('input' + enabled + ', select' + enabled + ', textarea' + enabled + '');
                elements.each(function() {
                    if ($(this).attr('type') == 'checkbox') {
                        if ($(this).is(':checked')) {
                            result[$(this).attr('name')] = 'on';
                        }
                    } else {
                        result[$(this).attr('name')] = $(this).val();
                    }
                });

                return result;
            }
        };
    };
})(jQuery);