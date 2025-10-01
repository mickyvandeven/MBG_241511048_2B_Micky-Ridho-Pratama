<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    // Dashboard Client
    Route::get('/client/dashboard', [ClientController::class, 'clientDashboard'])
        ->middleware('auth', 'role:dapur')
        ->name('client.dashboard');

    // Dashboard Admin
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])
        ->middleware(['auth', 'role:gudang'])
        ->name('admin.dashboard');
});
