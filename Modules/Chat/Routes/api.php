<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\ChatController;


Route::group(['middleware' => 'auth:api'], function () {

    Route::post('checkroom', [ChatController::class, 'check']);
    Route::post('read/{id}', [ChatController::class, 'read']);
    Route::post('store-message', [ChatController::class, 'store']);
    Route::post('room', [ChatController::class, 'room']);
    Route::post('get-soft', [ChatController::class, 'getMessage']);
    Route::post('delete-soft', [ChatController::class, 'delete']);
    Route::post('room-messages', [ChatController::class, 'message']);
    Route::post('latest-messages', [ChatController::class, 'latestMessage']);
    Route::post('delete/{id}', [ChatController::class, 'destroy']);

});
