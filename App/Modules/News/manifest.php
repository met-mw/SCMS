<?php
return [
    'version' => '1.0.0',
    'meta' => [
        'name' => 'News',
        'alias' => 'Новости',
        'description' => 'Формирование и отображение новостной ленты. Позволяет публиковать на сайте различные новости.',
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