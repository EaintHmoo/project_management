<?php

use App\Modules\Auth\Presentation\Http\Controllers\EmailVerificationController;
use App\Modules\Auth\Presentation\Http\Controllers\LoginController;
use App\Modules\Auth\Presentation\Http\Controllers\LogoutController;
use App\Modules\Auth\Presentation\Http\Controllers\PasswordResetController;
use App\Modules\Auth\Presentation\Http\Controllers\RegisterController;
use App\Modules\Auth\Presentation\Http\Controllers\SecurityController;
use App\Modules\Auth\Presentation\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', RegisterController::class);
Route::post('/auth/login', LoginController::class);
Route::post('/auth/two-factor/verify', [TwoFactorController::class, 'verify']);
Route::post('/auth/two-factor/resend', [TwoFactorController::class, 'resend']);

Route::post('/auth/forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('/auth/reset-password', [PasswordResetController::class, 'reset']);

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', LogoutController::class);
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend']);
    Route::get('/security/activity', [SecurityController::class, 'activity']);
});
