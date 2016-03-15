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
                    'modules': ['s-datagrid', 'module.catalogue']
                },
                {
                    'base-path': '/admin/modules/catalogue/edit',
                    'pattern': '^/admin/modules/catalogue/edit(/?)',
                    'modules': ['tinymce', 'tinymce-ru', 'tinymce-file-manager', 'module.catalogue.tinymce-config', 'module.catalogue.category.edit', 'module.catalogue.item.edit']
                }
                // Пользовательская часть
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