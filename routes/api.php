<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\StateController;
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
    Route::get('/categories',         [CategoryController::class, 'index']);
    Route::post('/categories',        [CategoryController::class, 'store']);
    Route::put('/categories/{id}',    [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Media
    Route::get('/media',         [MediaController::class, 'index']);
    Route::post('/media',        [MediaController::class, 'store']);
    Route::delete('/media/{id}', [MediaController::class, 'destroy']);

    // User Contacts
    Route::get('/contacts',         [UserContactController::class, 'index']);
    Route::post('/contacts',        [UserContactController::class, 'store']);
    Route::put('/contacts/{id}',    [UserContactController::class, 'update']);
    Route::delete('/contacts/{id}', [UserContactController::class, 'destroy']);

    // Countries
    Route::get('/countries',         [CountryController::class, 'index']);
    Route::post('/countries',        [CountryController::class, 'store']);
    Route::put('/countries/{id}',    [CountryController::class, 'update']);
    Route::delete('/countries/{id}', [CountryController::class, 'destroy']);

    // States
    Route::get('/states',         [StateController::class, 'index']);
    Route::post('/states',        [StateController::class, 'store']);
    Route::put('/states/{id}',    [StateController::class, 'update']);
    Route::delete('/states/{id}', [StateController::class, 'destroy']);

    // Cities
    Route::get('/cities',         [CityController::class, 'index']);
    Route::post('/cities',        [CityController::class, 'store']);
    Route::put('/cities/{id}',    [CityController::class, 'update']);
    Route::delete('/cities/{id}', [CityController::class, 'destroy']);

    // Companies
    Route::get('/companies',         [CompanyController::class, 'index']);
    Route::post('/companies',        [CompanyController::class, 'store']);
    Route::put('/companies/{id}',    [CompanyController::class, 'update']);
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);

    // Branches
    Route::get('/branches',         [BranchController::class, 'index']);
    Route::post('/branches',        [BranchController::class, 'store']);
    Route::put('/branches/{id}',    [BranchController::class, 'update']);
    Route::delete('/branches/{id}', [BranchController::class, 'destroy']);

    // Departments
    Route::get('/departments',         [DepartmentController::class, 'index']);
    Route::post('/departments',        [DepartmentController::class, 'store']);
    Route::put('/departments/{id}',    [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    // Designations
    Route::get('/designations',         [DesignationController::class, 'index']);
    Route::post('/designations',        [DesignationController::class, 'store']);
    Route::put('/designations/{id}',    [DesignationController::class, 'update']);
    Route::delete('/designations/{id}', [DesignationController::class, 'destroy']);

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