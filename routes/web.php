<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/comparison', function () {
    return view('branch-comparison');
});

// 3 Rute Baru
Route::get('/sales', function () {
    return view('sales');
});

Route::get('/settings', function () {
    return view('settings');
});

Route::get('/stock', function () {
    return view('stock');
});
