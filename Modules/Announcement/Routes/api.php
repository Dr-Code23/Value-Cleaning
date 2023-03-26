<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Announcement\Http\Controllers\AnnouncementController;

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

Route::middleware(['user_api'])->prefix("admin")->group(function () {
    Route::get('announcement', [AnnouncementController::class, 'index']);
    Route::post('create/announcement', [AnnouncementController::class, 'store']);
    Route::post('update/announcement/{id}', [AnnouncementController::class, 'update']);
    Route::get('show/announcement/{id}', [AnnouncementController::class, 'show']);
    Route::delete('delete/announcement/{id}', [AnnouncementController::class, 'destroy']);
    Route::get('active/announcement/{id}', [AnnouncementController::class, 'activate']);

});
