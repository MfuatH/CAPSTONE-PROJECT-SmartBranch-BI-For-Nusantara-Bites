<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RawMaterialController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index_alt');

    Route::post('/run-ai-forecast', [ForecastController::class, 'generateMassal'])->name('run.forecast');

    Route::get('/set-branch/{id?}', function ($id = null) {
        if ($id) {
            session(['branch_id' => $id]); 
        } else {
            session()->forget('branch_id'); 
        }
        return back(); 
    })->name('set.branch');

    Route::get('/riwayat-penjualan', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/import-dataset', [TransactionController::class, 'import'])->name('import.dataset');

    Route::get('/stok-inventaris', [RawMaterialController::class, 'index'])->name('inventory.index');

    Route::get('/comparison', function () {
        return view('branch-comparison');
    })->name('comparison.index');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
});