define(
    'router',
    [],
    function() {
        "use strict";

        return {
            path: window.location.pathname,
            routes: [
                // Админка
                {
                    'base-path': '/admin/modules/structures',
                    'pattern': '^/admin/modules/structures(/?)(\\?parent_pk=[0-9]+)?$',
                    'modules': ['s-datagrid']
                },
                {
                    'base-path': '/admin/modules/structures/edit',
                    'pattern': '^/admin/modules/structures/edit(/?)(\\?pk=[0-9]+)?$',
                    'modules': ['module.structure.edit']
                },
                {
                    'base-path': '/admin/modules/employees/authorization',
                    'pattern': '^/admin/modules/employees/authorization(/?)$',
                    'modules': ['module.employee.authorization']
                },
                {
                    'base-path': '/admin/modules/employees/registration',
                    'pattern': '^/admin/modules/employees/registration(/?)$',
                    'modules': ['module.employee.registration']
                },
                {
                    'base-path': '/admin/modules/employees',
                    'pattern': '^/admin/modules/employees(/?)(\\?pk=[0-9]+)?$',
                    'modules': ['s-datagrid']
                },
                {
                    'base-path': '/admin/modules/pages',
                    'pattern': '^/admin/modules/pages(/?)$',
                    'modules': ['s-datagrid']
                },
                {
                    'base-path': '/admin/modules/pages/edit',
                    'pattern': '^/admin/modules/pages/edit(/?)(\\?pk=[0-9]+)?$',
                    'modules': ['tinymce', 'tinymce-ru', 'tinymce-file-manager', 'module.pages.tinymce-config', 'module.pages.edit']
                },
                {
                    'base-path': '/admin/modules/employees/edit',
                    'pattern': '^/admin/modules/employees/edit(/?)(\\?pk=[0-9]+)?$',
                    'modules': ['module.employees.edit']
                },
                {
                    'base-path': '/admin/modules/catalogue',
                    'pattern': '^/admin/modules/catalogue(/?)',
                    'modules': ['s-datagrid', 'module.catalogue', 'scms.content-to-modal']
                },
                {
                    'base-path': '/admin/modules/catalogue/edit',
                    'pattern': '^/admin/modules/catalogue/edit(/?)',
                    'modules': ['tinymce', 'tinymce-ru', 'tinymce-file-manager', 'module.catalogue.tinymce-config', 'module.catalogue.category.edit', 'module.catalogue.item.edit']
                },
                {
                    'base-path': '/admin/modules/siteusers',
                    'pattern': '^/admin/modules/siteusers(/?)',
                    'modules': ['s-datagrid', 'scms.content-to-modal']
                },
                {
                    'base-path': '/admin/modules/siteusers/edit',
                    'pattern': '^/admin/modules/siteusers/edit(/?)',
                    'modules': ['module.siteuser.edit']
                },
                {
                    'base-path': '/admin/modules/frames',
                    'pattern': '^/admin/modules/frames(/?)',
                    'modules': ['s-datagrid', 'scms.content-to-modal']
                },
                {
                    'base-path': '/admin/modules/frames/edit',
                    'pattern': '^/admin/modules/frames/edit(/?)',
                    'modules': ['markitup.init', 'module.frames.edit']
                },
                {
                    'base-path': '/admin/modules/gallery',
                    'pattern': '^/admin/modules/gallery(/?)$',
                    'modules': ['s-datagrid', 'scms.content-to-modal']
                },
                {
                    'base-path': '/admin/modules/gallery/edit',
                    'pattern': '^/admin/modules/gallery/edit(/?)',
                    'modules': ['module.gallery.edit', 'module.gallery.init']
                },
                {
                    'base-path': '/admin/modules/gallery/item',
                    'pattern': '^/admin/modules/gallery/item(/?)',
                    'modules': ['s-datagrid', 'scms.content-to-modal', 'module.gallery']
                },
                {
                    'base-path': '/admin/modules/gallery/item/edit',
                    'pattern': '^/admin/modules/gallery/item/edit(/?)',
                    'modules': ['tinymce-file-manager', 'module.gallery.item.edit']
                },
                // Пользовательская часть
                {
                    'base-path': '/',
                    'pattern': '^(/?)',
                    'modules': ['module.catalogue.cart', 'scms.content-to-modal', 'module.gallery.init', 'fancybox-init']
                },
                {
                    'base-path': '/catalogue/',
                    'pattern': '^/catalogue(/?)',
                    'modules': ['module.catalogue', 'scms.content-to-modal']
                },
                {
                    'base-path': '/documents/',
                    'pattern': '^/documents(/?)',
                    'modules': ['jquery-mosaic-flow']
                }
            ],
            execute: function() {
                var self = this;
                _.each(this.routes, function(route) {
                    var regex = new RegExp(route.pattern);
                    if (regex.exec(self.path)) {
                        require(route.modules);
                    }
                });
            }
        }
    }
);