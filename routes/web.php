<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    return redirect()->route('admin.admin-list');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware('web')
    ->group(function() {
        Route::middleware('guest')->group(function() {
            Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])
                ->name('login');
            Route::post('/', [\App\Http\Controllers\Admin\AdminController::class, 'login'])
                ->name('submit-login');
        });

        Route::middleware(['auth:admin'])->group(function() {
            Route::post('logout', [\App\Http\Controllers\Admin\AdminController::class, 'logout'])
                ->name('logout');

            Route::get('admin-list', [\App\Http\Controllers\Admin\AdminController::class, 'adminList'])
                ->name('admin-list');
            Route::get('user-list', [\App\Http\Controllers\Admin\AdminController::class, 'userList'])
                ->name('user-list');

            Route::get('user/{username}', [\App\Http\Controllers\Admin\AdminController::class, 'userProfile'])
                ->name('user-profile');
            Route::post('user/{username}/block', [\App\Http\Controllers\Admin\AdminController::class, 'blockUser'])
                ->name('user-block');
            Route::post('user/{username}/unblock', [\App\Http\Controllers\Admin\AdminController::class, 'unblockUser'])
                ->name('user-unblock');

            Route::get('games', [\App\Http\Controllers\Admin\AdminController::class, 'games'])
                ->name('game-list');
            Route::delete('games/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteGame'])
                ->name('game-delete');

            Route::post('score/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteScores'])
                ->name('score.delete');
        });

});

Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'toLoginPage'])
    ->name('login');

Route::get('game/{slug}', [\App\Http\Controllers\Admin\AdminController::class, 'gameById'])
    ->name('game');

Route::get('games/{slug}/{version}', [\App\Http\Controllers\GameController::class, 'serveGame'])
    ->name('game.serve');

Route::get('games/{slug}/{version}/{file}', [\App\Http\Controllers\Admin\AdminController::class, 'getGameFile'])
    ->name('game.file');

Route::post('api/v1/games/{game}/upload', [\App\Http\Controllers\GameController::class, 'upload']);
