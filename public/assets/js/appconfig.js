requirejs.config({
    paths: {
        'router': 'router',
        'jquery': 'bower_components/jquery/dist/jquery.min',
        'bootstrap': 'bower_components/bootstrap/dist/js/bootstrap.min',
        'underscore': 'bower_components/underscore/underscore-min',
        'underscore.string': 'bower_components/underscore.string/dist/underscore.string.min',
        'tinymce': 'bower_components/tinymce/tinymce.min',
        'tinymce-ru': 'tinymce/langs/ru',
        'sform': 'forms/jquery.sform',
        'sajaxloader': 'jquery.sajaxloader',
        'modal': 'forms/modal',
        'entity': 'admin/entity',
        'module.structure.edit': '/App/Modules/Structures/public/assets/js/admin/form/structure',
        'module.employee.authorization': '/App/Modules/Employees/public/assets/js/admin/form/authorization',
        'module.employee.registration': '/App/Modules/Employees/public/assets/js/admin/form/registration',
        'module.pages.edit': '/App/Modules/Pages/public/assets/js/admin/form/page',
        'module.pages.tinymce-config': '/App/Modules/Pages/public/assets/js/admin/form/tinymce-config'
    },
    shim: {
        'jquery': {
            exports: '$'
        },
        'bootstrap': ['jquery', 'underscore'],
        'sform': ['jquery'],
        'modal': ['jquery'],
        'entity': ['jquery'],
        'underscore': {
            exports: '_'
        }
    }
});

require(
    ['app'],
    function(app) {
        "use strict";

        app.start();
    }
);