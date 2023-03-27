<?php

use Illuminate\Http\Request;
use Modules\Favorite\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['user_api', 'setlocale'])->group(function () {
    Route::get('Favorite', [FavoriteController::class, 'index']);
    Route::post('create/Favorite', [FavoriteController::class, 'store']);
    Route::get('show/Favorite/{id}', [FavoriteController::class, 'show']);
    Route::delete('delete/Favorite/{id}', [FavoriteController::class, 'destroy']);


});
