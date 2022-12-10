<?php

require __DIR__ . '/../vendor/autoload.php';

$router = new \Tether\Route();

\Tether\Database::mapConnections();

$router->handle(
    new \Tether\Request()
);

if (! \Tether\Config::get('view.cache.enabled', true)) {
    $config_dir = \Tether\Config::get('view.cache.directory');

    system('rm -rf -- ' . escapeshellarg($config_dir));
}