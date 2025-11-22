<?php

declare(strict_types=1);

use App\Http\Controllers\Media\UploadController;
use App\Models\Organization\Organization;
use Illuminate\Http\Response;

/**
 * This is where webshooks and other routes which does not require auth resides.
 *
 * Carefully place all routes in the section they belong here
 */

/**
 * APPLICATION ROUTES (without authentication).
 */
// Route::get('/unauthenticated', fn () => 'You dont not token to accesss me');
// Route::get('/get-organization/{subdomain}', fn($subdomain) => Response::success(Organization::whereHas('domains', fn($q) => $q->where('sub_domain', $subdomain))->first()));
// Route::post('/get-upload-url', [UploadController::class, 'getUploadUrl'])->name('get-upload-url');
// Route::post('/upload-complete/{id}', [UploadController::class, 'notifyUploadComplete'])->name('upload-complete');

// Route::as('media')->prefix('media')->group(function (): void {
//     // Route::post('/get-upload-url', [UploadController::class, 'getUploadUrl'])->name('get-upload-url');
//     Route::post('/{media:key}/status', [UploadController::class, 'checkStatus'])->name('check-status');
//     Route::post('/upload', [UploadController::class, 'directUpload'])->name('direct-upload');
//     Route::post('/{media:key}/mark-as-complete', [UploadController::class, 'markMediaAsUploadComplete'])->name('direct-upload');
// });

/**
 * WEBHOOKS.
 */
