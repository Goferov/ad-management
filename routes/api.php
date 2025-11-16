<?php

use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\AdStatsController;
use App\Http\Controllers\Api\TrackingController;
use Illuminate\Support\Facades\Route;

Route::prefix('ads')->group(function () {
    Route::post('/', [AdController::class, 'store']);
    Route::get('{id}', [AdController::class, 'show']);

});

Route::get('ad-stats', [AdStatsController::class, 'index']);
Route::post('track', [TrackingController::class, 'store']);
