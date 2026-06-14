<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserBankAccountController;
use App\Http\Controllers\UserContactController;
use App\Http\Controllers\UserScreenshotController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Configuration Management
    Route::get('/configs',       [ConfigController::class, 'index']);
    Route::post('/configs',      [ConfigController::class, 'store']);
    Route::put('/configs/{key}', [ConfigController::class, 'update']);

    // Categories
    Route::get('/categories',        [CategoryController::class, 'index']);
    Route::post('/categories',       [CategoryController::class, 'store']);
    Route::put('/categories/{id}',   [CategoryController::class, 'update']);
    Route::delete('/categories/{id}',[CategoryController::class, 'destroy']);

    // Media
    Route::get('/media',        [MediaController::class, 'index']);
    Route::post('/media',       [MediaController::class, 'store']);
    Route::delete('/media/{id}',[MediaController::class, 'destroy']);

    // User Contacts
    Route::get('/contacts',         [UserContactController::class, 'index']);
    Route::post('/contacts',        [UserContactController::class, 'store']);
    Route::put('/contacts/{id}',    [UserContactController::class, 'update']);
    Route::delete('/contacts/{id}', [UserContactController::class, 'destroy']);

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