<?php
return [
    'version' => '1.0.0',
    'meta' => [
        'name' => 'Gallery',
        'alias' => 'Галерея',
        'description' => 'Модуль управления галлереями изображений.',
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