<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware(['forcejson', 'check.block.user'])->group(function() {
//    Роуты для аутентификации пользователей
    Route::prefix('auth')->group(function() {
        Route::post('signup', [\App\Http\Controllers\User\AuthController::class, 'register']);
        Route::post('signin', [\App\Http\Controllers\User\AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function() {
            Route::post('signout', [\App\Http\Controllers\User\AuthController::class, 'logout']);
        });
    });
//  Роуты для управления играми
    Route::middleware(['auth:sanctum'])->group(function() {
        Route::middleware(['check.game.author'])->group(function() {
            Route::resource('games', \App\Http\Controllers\GameController::class)
            ->only(['update', 'destroy']);
        });
        Route::resource('games', \App\Http\Controllers\GameController::class)
            ->only(['store']);
    });
    Route::resource('games', \App\Http\Controllers\GameController::class)
        ->only(['index', 'show']);

//    Роуты для информации пользователей
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('users/{user}', [\App\Http\Controllers\GameController::class, 'user']);
    });

//    Роуты для получения очков
    Route::get('games/{game}/scores', [\App\Http\Controllers\GameController::class, 'scores']);
    Route::post('games/{game}/scores', [\App\Http\Controllers\GameController::class, 'postScore']);
});
