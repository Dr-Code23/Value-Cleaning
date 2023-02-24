<?php

use Illuminate\Http\Request;
use Modules\Order\Http\Controllers\OrderController;

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

Route::middleware('auth:api')->get('/order', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('Active/Order/{id}', [OrderController::class, 'activate']);
    Route::get('Order', [OrderController::class, 'index']);
    Route::get('CansaledOrder', [OrderController::class, 'CansaledOrder']);
    Route::get('Cansal/{id}', [OrderController::class, 'Cansale']);
    Route::get('Order_Code/{id}', [OrderController::class, 'OrderCode']);

    Route::get('FinishedOrder', [OrderController::class, 'FinishedOrder']);
    Route::post('create/Order', [OrderController::class, 'store']);
    Route::post('update/Order/{id}', [OrderController::class, 'update']);
    Route::get('show/Order/{id}', [OrderController::class, 'show']);
    Route::post('delete/Order/{id}', [OrderController::class, 'destroy']);


});
