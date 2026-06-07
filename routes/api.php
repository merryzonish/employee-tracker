<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserScreenshotController;
use App\Http\Controllers\UserActivityController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Screenshots
    Route::get('/screenshots',          [UserScreenshotController::class, 'index']);
    Route::post('/screenshots/store',   [UserScreenshotController::class, 'store']);
    Route::post('/screenshots/capture', [UserScreenshotController::class, 'capture']);
    Route::post('/screenshots/delete',  [UserScreenshotController::class, 'destroy']);

    // Activity Tracking
    Route::post('/track/activity',      [UserActivityController::class, 'store']);
    Route::get('/track/activity/stats', [UserActivityController::class, 'stats']);

});