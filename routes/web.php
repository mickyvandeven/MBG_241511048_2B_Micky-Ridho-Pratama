<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BahanBakuController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard Client
    Route::get('/client/dashboard', [ClientController::class, 'clientDashboard'])
        ->middleware('auth', 'role:dapur')
        ->name('client.dashboard');

    // Dashboard Admin
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])
        ->middleware(['auth', 'role:gudang'])
        ->name('admin.dashboard');

    // Routes untuk Input Bahan Baku (Admin Only)
    Route::middleware(['role:gudang'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('bahan-baku/create', [BahanBakuController::class, 'create'])->name('bahan-baku.create');
        Route::post('bahan-baku', [BahanBakuController::class, 'store'])->name('bahan-baku.store');
    });
});
