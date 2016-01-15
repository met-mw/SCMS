(function($){
    $.fn.sAjaxLoader = function() {
        return {
            container: this,
            loader: $('<img src="/public/assets/images/system/ajax-loader.gif" />'),
            show: function() {
                this.container.append(this.loader)
            },
            hide: function() {
                this.loader.remove();
            }
        };
    };
})(jQuery);