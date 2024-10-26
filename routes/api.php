<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController,
    RecoveryPasswordController,
    ResetPasswordController,
    DeleteAccountController,
};

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');
Route::post('password-recovery', RecoveryPasswordController::class)->name('password.recovery');
Route::post('password-reset', ResetPasswordController::class)->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('users', DeleteAccountController::class)->name('users.delete');
});
