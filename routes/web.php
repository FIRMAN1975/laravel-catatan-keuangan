<?php

use App\Http\Controllers\AuthController;
use App\Livewire\CashflowDetailLivewire;
use App\Livewire\HomeLivewire;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {
    // Halaman utama cashflow - GUNAKAN LIVEWIRE
    Route::get('/home', HomeLivewire::class)->name('app.home');

    // Halaman detail cashflow - GUNAKAN LIVEWIRE
    Route::get('/cashflow/{cashflow_id}', CashflowDetailLivewire::class)
        ->name('app.cashflow.detail');
});

// Redirect default ke home
Route::get('/', function () {
    return redirect()->route('app.home');
});