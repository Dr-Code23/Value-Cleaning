<?php

use Illuminate\Http\Request;
use Modules\Expenses\Http\Controllers\ExpenseController;
use Modules\Expenses\Http\Controllers\PaymentController;
use Modules\Expenses\Http\Controllers\TypeController;




Route::middleware('auth:api')->get('/expenses', function () {
Route::post('store-expense', [ExpenseController::class, 'store']);
Route::get('get-expense', [ExpenseController::class, 'index']);
Route::post('update-expense', [ExpenseController::class, 'update']);
Route::post('delete-expense', [ExpenseController::class, 'destroy']);
Route::post('edit-expense', [ExpenseController::class, 'edit']);

Route::post('store-type', [TypeController::class, 'store']);
Route::get('get-type', [TypeController::class, 'index']);
Route::post('update-type', [TypeController::class, 'update']);
Route::post('delete-type', [TypeController::class, 'destroy']);
Route::post('edit-type', [TypeController::class, 'edit']);

Route::post('store-payment', [PaymentController::class, 'store']);
Route::get('get-payment', [PaymentController::class, 'index']);
Route::post('update-payment', [PaymentController::class, 'update']);
Route::post('delete-payments', [PaymentController::class, 'destroy']);
Route::post('edit-payment', [PaymentController::class, 'edit']);

});
