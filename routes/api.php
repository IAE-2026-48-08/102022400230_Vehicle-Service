<?php

use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\SsoProfileController;
use App\Http\Controllers\Api\V1\VehicleDispatchController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('iaekey')
    ->group(function () {
        Route::get('/vehicles', [VehicleController::class, 'index']);
        Route::get('/vehicles/{id}', [VehicleController::class, 'show']);
        Route::post('/vehicles', [VehicleController::class, 'store']);
    });

Route::prefix('v1')
    ->middleware('sso')
    ->group(function () {
        Route::get('/sso/profile', [SsoProfileController::class, 'show']);
        Route::post('/vehicles/{id}/dispatch', [VehicleDispatchController::class, 'store']);
    });
