<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserScreenshotController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Configuration Management
    Route::get('/configs',       [ConfigController::class, 'index']);
    Route::post('/configs',      [ConfigController::class, 'store']);
    Route::put('/configs/{key}', [ConfigController::class, 'update']);

    // Tracker Routes — IP Validation Applied
    Route::middleware('validate.ip')->group(function () {

        // Screenshots
        Route::get('/screenshots',          [UserScreenshotController::class, 'index']);
        Route::post('/screenshots/store',   [UserScreenshotController::class, 'store']);
        Route::post('/screenshots/capture', [UserScreenshotController::class, 'capture']);
        Route::post('/screenshots/delete',  [UserScreenshotController::class, 'destroy']);

        // Activity Tracking
        Route::post('/track/activity',      [UserActivityController::class, 'store']);
        Route::get('/track/activity/stats', [UserActivityController::class, 'stats']);

    });

});