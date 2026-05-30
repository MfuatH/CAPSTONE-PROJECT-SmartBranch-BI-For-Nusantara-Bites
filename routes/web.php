<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RawMaterialController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/comparison', function () {
    return view('branch-comparison');
});

// Transaction routes
Route::get('/riwayat-penjualan', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/import-dataset', [TransactionController::class, 'import'])->name('import.dataset');

Route::get('/settings', function () {
    return view('settings');
});

Route::get('/stok-inventaris', [RawMaterialController::class, 'index'])->name('inventory.index');
