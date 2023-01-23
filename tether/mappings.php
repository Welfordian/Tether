<?php

return [
    'classes' => [
        'blade' => \Jenssegers\Blade\Blade::class,
        'view' => \Tether\View::class,
        'config' => \Tether\Config::class,
        'exception' => \Tether\Exception::class,
        'session' => \Tether\Session::class,
        'auth' => \Tether\Auth::class,
        'redirect' => \Tether\Redirect::class,
        'request' => \Tether\Request::class,
        'route' => \Tether\Route::class,
        'database' => \Tether\Database::class,
    ],
    
    'aliases' => [
        \Jenssegers\Blade\Blade::class => 'blade',
    ]
];