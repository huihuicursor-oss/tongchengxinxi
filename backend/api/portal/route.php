<?php

use think\facade\Route;

Route::group('portal', function () {
    Route::get('bootstrap', 'portal/Bootstrap/index');
    Route::get('cities', 'portal/Bootstrap/cities');
    Route::get('guess-city', 'portal/Bootstrap/guessCity');
    Route::get('params', 'portal/Bootstrap/params');

    Route::get('channels', 'portal/Content/channels');
    Route::get('content', 'portal/Content/index');
    Route::get('content/:id', 'portal/Content/read');
    Route::post('content', 'portal/Content/save');

    Route::get('news', 'portal/News/index');
    Route::get('news/:id', 'portal/News/read');

    Route::get('merchants', 'portal/Merchant/index');
    Route::get('merchants/:id/comments', 'portal/Merchant/comments');
    Route::get('merchants/:id', 'portal/Merchant/read');
    Route::post('merchant-comment', 'portal/Merchant/comment');
    Route::post('merchant-apply', 'portal/Merchant/apply');

    Route::get('discovery', 'portal/Discovery/index');
    Route::get('discovery/topics', 'portal/Discovery/topics');
    Route::get('discovery/friends', 'portal/Discovery/friends');
    Route::get('discovery/:id', 'portal/Discovery/read');
    Route::post('discovery', 'portal/Discovery/save');

    Route::post('auth/login', 'portal/Auth/login');
    Route::post('auth/register', 'portal/Auth/register');

    Route::get('search/content', 'portal/Search/content');
    Route::get('search/merchants', 'portal/Search/merchants');

    Route::get('user/dashboard', 'portal/User/dashboard');
    Route::get('user/collections', 'portal/User/collections');
    Route::get('user/messages', 'portal/User/messages');
    Route::get('user/help', 'portal/User/help');
    Route::get('user/vip', 'portal/User/vip');
    Route::post('user/collect', 'portal/User/collect');
    Route::post('user/feedback', 'portal/User/feedback');
});

// Compatibility aliases for the observed H5 bundle.
Route::get('sybmenhu.common/get_city_by_lat', 'portal/Bootstrap/guessCity');
Route::get('sybmenhu.common/area_tree', 'portal/Bootstrap/cities');
Route::get('sybmenhu.common/params', 'portal/Bootstrap/params');
Route::get('sybmenhu.user/my_auth', 'portal/User/authStatus');
Route::post('sybmenhu.user/wxlogin', 'portal/Auth/wxLogin');
