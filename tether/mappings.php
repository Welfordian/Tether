<?php

return [
    'classes' => [
        'blade' => \Jenssegers\Blade\Blade::class,
        'config' => \Tether\Config::class,
        'database' => \Tether\Database::class,
        'request' => \Tether\Request::class,
        'route' => \Tether\Route::class,
        'view' => \Tether\View::class,
    ],
    
    'aliases' => [
        \Jenssegers\Blade\Blade::class => 'blade'
    ]
];