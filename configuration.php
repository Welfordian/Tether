<?php

return [
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
    
    'view' => [
        'template_directory' => __DIR__ . '/templates',
        
        'cache' => [
            'enabled' => false,
            'directory' => __DIR__ . '/tether/cache'
        ]
    ]
];