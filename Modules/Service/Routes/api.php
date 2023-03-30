<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\Admin\ServiceController;
use Modules\Service\Http\Controllers\Admin\SubServiceController;
use Modules\Service\Http\Controllers\User\HomeController;


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


Route::middleware(['user_api', 'setlocale'])->prefix("admin")->group(function () {
    Route::post('create/service', [ServiceController::class, 'store']);
    Route::post('update/service/{id}', [ServiceController::class, 'update']);
    Route::delete('delete/service/{id}', [ServiceController::class, 'destroy']);
    Route::get('Active/service/{id}', [ServiceController::class, 'activate']);
    Route::post('find/service/{id}', [ServiceController::class, 'addServiceWorker']);
    Route::post('Delete/WorkerFromService/{id}', [ServiceController::class, 'deleteWorkerFromService']);
    Route::get('service', [ServiceController::class, 'index']);
    Route::get('show/service/{id}', [ServiceController::class, 'show']);
    Route::get('SubService', [SubServiceController::class, 'index']);
    Route::get('show/SubService/{id}', [SubServiceController::class, 'show']);
    Route::get('SubService/with/service/{id}', [SubServiceController::class, 'showWith']);
    Route::post('create/SubService', [SubServiceController::class, 'store']);
    Route::post('update/SubService/{id}', [SubServiceController::class, 'update']);
    Route::delete('delete/SubService/{id}', [SubServiceController::class, 'destroy']);


});
Route::middleware(['user_api', 'setlocale'])->group(function () {
    Route::get('userHome', [HomeController::class, 'userHome']);
    Route::get('all/SubService/{id}', [HomeController::class, 'subService']);
    Route::get('all/requirement/{id}', [HomeController::class, 'requirement']);
    Route::get('service/{id}', [HomeController::class, 'serviceDetails']);
    Route::get('jobDone/{id}', [HomeController::class, 'jobDone']);
    Route::get('top-services', [HomeController::class, 'topServices']);
    Route::get('userHome', [HomeController::class, 'userHome']);


});
