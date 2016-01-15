define(
    'app',
    [
        'router',
        'bootstrap'
    ],
    function (router) {
        "use strict";

        return window.app = {
            router: router,
            start: function() {
                this.router.execute();
            }
        };
    }
);