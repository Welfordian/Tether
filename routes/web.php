<?php

use \Tether\Route;

Route::get('/', ['IndexController', 'show']);
Route::post('/', ['IndexController', 'handle']);