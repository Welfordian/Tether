<?php

use \Tether\Route;

Route::get('/', [\App\Http\Controllers\IndexController::class, 'show']);
Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
Route::get('/logout', [\App\Http\Controllers\LoginController::class, 'logout']);
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'show']);