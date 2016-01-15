<?php
return [
    'version' => '1.0.0',
    'meta' => [
        'name' => 'Pages',
        'alias' => 'Статичные страницы',
        'description' => 'Создание и редактирование статичных страниц сайта. Страницы, созданные при помощи данного модуля могут быть использованы для отображения в произвольных разделах сайта.',
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