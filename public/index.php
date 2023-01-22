<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Tether\App();

(require(__DIR__ . '/../tether/helpers.php'))($app);

$app->initialize();
$app->run();