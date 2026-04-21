<?php

use think\facade\Route;

Route::get('/', 'elderlyCare/index');
Route::get('dashboard', 'elderlyCare/dashboard');
Route::get('elders', 'elderlyCare/elders');
Route::get('services', 'elderlyCare/services');
Route::get('health', 'elderlyCare/health');
Route::get('staff', 'elderlyCare/staff');
Route::get('activities', 'elderlyCare/activities');
Route::get('hello/:name', 'index/hello');
