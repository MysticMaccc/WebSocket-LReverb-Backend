<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('notifications', NotificationController::class)->only(['index', 'store', 'destroy']);
    Route::get('notifications/count', [NotificationController::class, 'countNotification']);
});
