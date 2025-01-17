<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('throttle:auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});


Route::middleware('throttle:global')->group(function () {
    Route::middleware([JwtMiddleware::class])->group(function () {
        Route::get('user', [AuthController::class, 'getUser']);
        Route::post('logout', [AuthController::class, 'logout']);

        Route::resource('posts',PostController::class);

    });
});
