requirejs.config({
    paths: {
        'scms.content-to-modal': '/public/assets/js/library/s-content-to-modal',
        'text': 'bower_components/text/text',
        'router': 'router',
        'jquery': 'bower_components/jquery/dist/jquery.min',
        'jquery-tmpl': 'bower_components/jquery-tmpl/jquery.tmpl.min',
        'jquery-fancybox': '/public/assets/js/fancybox2/source/jquery.fancybox.pack',
        'jquery-textchange': 'bower_components/jquery-textchange/jquery.textchange',
        'jquery-mosaic-flow': 'bower_components/jquery.mosaicflow/jquery.mosaicflow.min',
        'jquery-galleria': 'bower_components/jquery-galleria/src/galleria',
        'jquery-lightbox': 'bower_components/lightbox2/src/js/lightbox',
        'jquery-zoom': 'bower_components/jquery-zoom/jquery.zoom',
        'jquery-galleria-classic-theme': 'bower_components/jquery-galleria/src/themes/classic/galleria.classic',
        'bootstrap': 'bower_components/bootstrap/dist/js/bootstrap.min',
        'underscore': 'bower_components/underscore/underscore-min',
        'underscore.string': 'bower_components/underscore.string/dist/underscore.string.min',
        'tinymce': 'bower_components/tinymce/tinymce.min',
        'tinymce-ru': 'tinymce/langs/ru',
        'tinymce-file-manager': 'tinymce/plugins/responsivefilemanager/plugin.min',
        'markitup.init': '/public/assets/js/library/markitup.init',
        'jquery.markitup': 'bower_components/markitup/markitup/jquery.markitup',
        'markitup.sets': 'bower_components/markitup/markitup/sets/default/set',
        'fancybox-init': 'main/fancybox-init',
        'sform': 'forms/jquery.sform',
        'sajaxloader': 'jquery.sajaxloader',
        'modal': 'forms/modal',
        'modal-popup': 'library/modal-popup',
        'entity': 'admin/entity',
        's-datagrid': 'library/s-datagrid',
        's-confirmation': 'library/s-confirmation',
        's-notification': 'library/s-notification',
        's-information': 'library/s-information',
        'module.structure.edit': '/App/Modules/Structures/public/assets/js/admin/form/structure',
        'module.employee.authorization': '/App/Modules/Employees/public/assets/js/admin/form/authorization',
        'module.employee.registration': '/App/Modules/Employees/public/assets/js/admin/form/registration',
        'module.pages.edit': '/App/Modules/Pages/public/assets/js/admin/form/page',
        'module.pages.tinymce-config': '/App/Modules/Pages/public/assets/js/admin/form/tinymce-config',
        'module.employees.edit': '/App/Modules/Employees/public/assets/js/admin/form/employee',
        'module.catalogue': '/App/Modules/Catalogue/public/assets/js/admin/catalogue',
        'module.catalogue.category.edit': '/App/Modules/Catalogue/public/assets/js/admin/form/catalogue-category',
        'module.catalogue.item.edit': '/App/Modules/Catalogue/public/assets/js/admin/form/catalogue-item',
        'module.catalogue.tinymce-config': '/App/Modules/Catalogue/public/assets/js/admin/form/tinymce-config',
        'module.catalogue.cart': '/App/Modules/Catalogue/public/assets/js/cart',
        'module.siteuser.edit': '/App/Modules/Siteusers/public/assets/js/admin/form/siteuser',
        'module.frames.edit': '/App/Modules/Frames/public/assets/js/admin/form/frame',
        'module.gallery': '/App/Modules/Gallery/public/assets/js/admin/gallery',
        'module.gallery.edit': '/App/Modules/Gallery/public/assets/js/admin/form/gallery',
        'module.gallery.item.edit': '/App/Modules/Gallery/public/assets/js/admin/form/gallery-item',
        'module.gallery.init': '/App/Modules/Gallery/public/assets/js/gallery-init'
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