<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


Route::middleware(['user_api', 'setlocale'])->group(function () {
    Route::get('active/Order/{id}', [OrderController::class, 'activate']);
    Route::get('cancel/{id}', [OrderController::class, 'cancel']);
    Route::get('order_Code/{id}', [OrderController::class, 'orderCode']);
    Route::post('create/Order', [OrderController::class, 'store']);
    Route::post('update/Order/{id}', [OrderController::class, 'update']);
    Route::post('delete/Order/{id}', [OrderController::class, 'destroy']);
    Route::get('/show/order/{id}', [OrderController::class, 'show']);
    Route::get('finishedOrder', [OrderController::class, 'finishedOrder']);
    Route::get('canceled/Order', [OrderController::class, 'canceledOrder']);
    Route::get('order', [OrderController::class, 'index']);
    Route::get('/order/pdf/{id}', [OrderController::class, 'createPdf']);
    Route::post('add-payment', [StripeController::class, 'makePayment']);
    Route::get('all-payment', [StripeController::class, 'allPayment']);
    Route::post('checkout-payment', [StripeController::class, 'checkoutPayment']);
    Route::post('delete-payment', [StripeController::class, 'deletePayment']);
});

Route::middleware(['user_api', 'permission:order-list|order-create|order-edit|order-delete', 'setlocale'])->prefix("admin")->group(function () {
    Route::post('worker/update/Order/{id}', [OrderAdminController::class, 'updateOrderToAdmin']);
    Route::post('states/Order/{id}', [OrderAdminController::class, 'changeStates']);
    Route::get('order', [OrderAdminController::class, 'index']);
    Route::get('canceledOrder', [OrderAdminController::class, 'canceledOrder']);
    Route::get('finishedOrder', [OrderAdminController::class, 'finishedOrder']);
    Route::get('show/order/{id}', [OrderAdminController::class, 'show']);
    Route::delete('delete/Order/{id}', [OrderAdminController::class, 'destroy']);
});
