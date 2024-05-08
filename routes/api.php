<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AdminFrameController;
use App\Http\Controllers\Api\Admin\AdminLensController;
use App\Http\Controllers\Api\User\UserFrameController;
use App\Http\Controllers\Api\User\UserLensController;
use App\Http\Controllers\Api\User\CustomGlassesController;



Route::prefix('admin')->group(function () {
    Route::apiResource('frames', AdminFrameController::class)->only(['index', 'store']);
    Route::apiResource('lenses', AdminLensController::class)->only(['index', 'store']);
});

Route::get('/frames', [UserFrameController::class, 'index']);
Route::get('/lenses', [UserLensController::class, 'index']);
Route::post('/custom-glasses', [CustomGlassesController::class, 'store']);

