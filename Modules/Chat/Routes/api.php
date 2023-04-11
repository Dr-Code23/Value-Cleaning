<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\ChatController;


Route::group(['middleware' => 'auth:api'], function () {

    Route::post('checkroom', [ChatController::class, 'check']);
    Route::post('read/{id}', [ChatController::class, 'read']);
    Route::get('check-user-admin', [ChatController::class, 'checkRoom']);
    Route::get('get-room-user', [ChatController::class, 'getUser']);
    Route::post('store-message', [ChatController::class, 'store']);
    Route::post('message-admin', [ChatController::class, 'storeMessage']);
    Route::post('room', [ChatController::class, 'room']);
    Route::post('get-soft', [ChatController::class, 'getMessage']);
    Route::post('delete-soft', [ChatController::class, 'delete']);
    Route::post('room-messages', [ChatController::class, 'message']);
    Route::get('all-room-message', [ChatController::class, 'index']);
    Route::post('latest-messages', [ChatController::class, 'latestMessage']);
    Route::post('delete/{id}', [ChatController::class, 'destroy']);
    Route::post('/agora/call-user', [ChatController::class, 'callUser']);
    Route::post('/generate-token', [ChatController::class, 'generateToken']);


});
