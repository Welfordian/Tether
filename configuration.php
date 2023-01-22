<?php

$config =  [
    'environment' => [
        'debug' => true,
    ],
    
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
    
    'session' => [
        'name' => 'tether',
        'lifetime' => '86400',
        'secure' => true,
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