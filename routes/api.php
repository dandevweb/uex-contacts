<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController,
    RecoveryPasswordController,
    ResetPasswordController,
    DeleteAccountController,
};
use App\Http\Controllers\{ContactController, AddressController};

Route::post('register', RegisterController::class)->name('register');
Route::post('login', LoginController::class)->name('login');
Route::post('password-recovery', RecoveryPasswordController::class)->name('password.recovery');
Route::put('password-reset', ResetPasswordController::class)->name('password.reset');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('users', DeleteAccountController::class)->name('users.delete');

    Route::apiResource('contacts', ContactController::class);

    Route::get('address/zip_code', [AddressController::class, 'getAddressByZipCode'])->name('address.zip_code');
    Route::get('address/suggestions', [AddressController::class, 'suggestAddresses'])->name('address.suggestions');
});
