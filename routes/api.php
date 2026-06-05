<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserScreenshotController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/screenshots',         [UserScreenshotController::class, 'index']);
Route::post('/screenshots/store',  [UserScreenshotController::class, 'store']);
 Route::post('/screenshots/capture',[UserScreenshotController::class, 'capture']);
});