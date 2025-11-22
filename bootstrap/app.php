<?php

use App\Exceptions\DisplayableException;
use App\Http\Middleware\EnforceJsonResponse;
use App\Utils\Exceptions\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::prefix('api/v1')->middleware([
                'api',
            ])->group(function (): void {
                // Routes without authentication
                Route::as('guest.')->group([
                    base_path('routes/v1/guest.php'),
                ]);

                // Routes for managing authentication
                Route::as('auth.')->group([
                    base_path('routes/v1/auth.php'),
                ]);

                // Routes that require authentication
                Route::middleware(['auth:sanctum'])->as('app.')->group([
                    base_path('routes/v1/api.php'),
                ]);
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend([
            EnforceJsonResponse::class,
        ]);

        $middleware->alias([
            'doNotCacheResponse' => Spatie\ResponseCache\Middlewares\DoNotCacheResponse::class,
            'cacheResponse' => Spatie\ResponseCache\Middlewares\CacheResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // List of Exceptions that should not be logged..
        $exceptions->dontReport([
            DisplayableException::class,
        ]);

        // TODO: Uncomment this to log on sentry
        // Integration::handles($exceptions);

        $exceptions->renderable(function (Throwable $e, Request $r) {

            $response = (new ExceptionHandler)->report($e);

            if ($response) {
                return $response;
            }

            if (app()->environment('production')) {
                return response()->error(message: 'An error occurred, please try again later.', http_code: Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
