<?php

use app\middleware\AuthMiddleware;
use think\facade\Route;

Route::get('/', 'index/index');
Route::get('login', 'auth/loginForm');
Route::post('login', 'auth/login');
Route::get('logout', 'auth/logout');

Route::group(function () {
    Route::get('dashboard', 'elderlyCare/dashboard');
    Route::get('elders', 'elderlyCare/elders');
    Route::get('elders/create', 'elderlyCare/create');
    Route::post('elders/create', 'elderlyCare/store');
    Route::get('elders/:id/edit', 'elderlyCare/edit')->pattern(['id' => '\d+']);
    Route::post('elders/:id/edit', 'elderlyCare/update')->pattern(['id' => '\d+']);
    Route::get('elders/:id', 'elderlyCare/show')->pattern(['id' => '\d+']);
    Route::get('services', 'elderlyCare/services');
    Route::get('health', 'elderlyCare/health');
    Route::get('staff', 'elderlyCare/staff');
    Route::get('activities', 'elderlyCare/activities');
})->middleware(AuthMiddleware::class);

Route::get('hello/:name', 'index/hello');
