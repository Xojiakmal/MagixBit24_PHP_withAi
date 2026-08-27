<?php

use App\Http\Controllers\Auth\BotLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [BotLoginController::class, 'show'])->name('login');
    Route::get('login/check/{token}', [BotLoginController::class, 'check'])->name('login.check');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
