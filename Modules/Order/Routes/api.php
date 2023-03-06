<?php

use Illuminate\Http\Request;
use Modules\Order\Http\Controllers\Admin\OrderAdminController;
use Modules\Order\Http\Controllers\User\OrderController;
use Modules\Order\Http\Controllers\User\StripeController;

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


Route::middleware(['user_api','role:user'])->group(function() {
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
    Route::get('/show/order/{id}', [OrderController::class, 'showOrder']);
    Route::get('/order/pdf/{id}', [OrderController::class, 'createPdf']);
    Route::post('make-payment',[StripeController::class,'makePayment']);




});
Route::middleware(['user_api','role:admin'])->prefix("admin")->group(function() {
    Route::post('Worker/update/Order/{id}', [OrderAdminController::class, 'UpdateOeserToAdmin']);
    Route::post('Status/Order/{id}', [OrderAdminController::class, 'ChangeStutes']);
    Route::get('Order', [OrderAdminController::class, 'index']);
    Route::get('CansaledOrder', [OrderAdminController::class, 'CansaledOrder']);

    Route::get('FinishedOrder', [OrderAdminController::class, 'FinishedOrder']);
    Route::put('update/Order/{id}', [OrderAdminController::class, 'update']);
    Route::get('show/Order/{id}', [OrderAdminController::class, 'show']);
    Route::delete('delete/Order/{id}', [OrderAdminController::class, 'destroy']);
});
