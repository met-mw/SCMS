define(
    'markitup.init',
    [
        'jquery',
        'markitup.sets',
        'jquery.markitup'
    ],
    function ($, settings) {
        $(document).ready(function() {
            $("#frame-content").markItUp(settings);
        });
    }
);