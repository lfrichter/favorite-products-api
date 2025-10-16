<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('users', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());

    Route::apiResource('users', UserController::class)->except(['store']);
    Route::apiResource('products', ProductController::class)->only(['index']);
    Route::apiResource('favorites', FavoriteProductController::class)->except(['show', 'update']);
});
