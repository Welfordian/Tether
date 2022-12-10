<?php

$config =  [
    'database' => [
        'connections' => [
            'default' => [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'tether',
                'charset' => 'utf8mb4'
            ]
        ]
    ],
    
    'blade' => [
        'viewPaths' => __DIR__ . '/templates',
        'cachePath' => __DIR__ . '/tether/cache',
        
        'cache' => [
            'enabled' => false,
        ]
    ]
];

return $config;