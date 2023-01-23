<?php

use \Tether\Route;

Route::get('/', [\App\Http\Controllers\IndexController::class, 'show'])->name('index')->middleware(['redirectIfAuthenticated']);

Route::group(['middleware' => 'csrf'], function () {
    Route::post('login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout')->middleware(['csrf']);
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'show'])->name('dashboard');
});