<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProviderAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',    [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('provider')->group(function () {
    Route::post('/register', [ProviderAuthController::class, 'register']);
    Route::post('/login',    [ProviderAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me',     [ProviderAuthController::class, 'me']);
        Route::post('/logout',[ProviderAuthController::class, 'logout']);
    });
});