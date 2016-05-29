<?php
return [
    'version' => '1.0.0',
    'meta' => [
        'name' => 'Catalogue',
        'alias' => 'Каталог товаров',
        'description' => 'Управление товарами и услугами организации. Позволяет организовать полноценный интернет-магазин.
        Модуль поддерживает неограниченную вложенность категорий и способен обрабатывать неограниченное количество товаров и услуг.',
    ],
    'author' => [
        'nick' => 'met-mw',
        'email' => 'met-mw@rambler.ru',
        'name' => 'Метрюк Михаил Владимирович'
    ],
    'dependencies' => [
        'Modules',
        'Structure'
    ],
    'run' => [
        'controller' => 'Controllers\\ControllerMain',
        'action' => 'actionIndex'
    ]
];