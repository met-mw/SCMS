<?php
return [
    'version' => '1.0.0',
    'meta' => [
        'name' => 'CatalogueRetriever',
        'alias' => 'Каталог',
        'description' => 'Управление товарами и их представление пользователю в удобном виде.',
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