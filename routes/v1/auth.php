<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => Response::success(message: 'Hurray!! App is working..'));

Route::controller(AuthController::class)->middleware('throttle:authentication')
    ->group(function (): void {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/forgot-password', 'forgotPassword');
    });
