<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::put('/me/logout', [UserController::class, 'logout'])->name('logout');
Route::apiSingleton('/me', UserController::class)->middleware('cacheResponse:1000,me');;

Route::as('verify')->middleware('throttle:authentication')->group(function (): void {
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('check');
    Route::post('/resend-verification-otp', [AuthController::class, 'resendVerificationCode'])->name('resend');
});

// Verified Users Only Access
Route::middleware(['verified'])->group(function (): void {
    Route::get('/campaigns/{campaign}/messages', [CampaignController::class, 'campaignMessages'])->name('campaigns.messages');
    Route::apiResource('/campaigns', CampaignController::class)->middleware('cacheResponse:300,campaigns');;
    Route::apiResource('/contacts', ContactController::class)->middleware('cacheResponse:300,contacts');;
});
