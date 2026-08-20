<?php

use App\Modules\Meetings\Presentation\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations/{organization}/meetings', [MeetingController::class, 'index']);
    Route::post('/organizations/{organization}/meetings', [MeetingController::class, 'store']);
    Route::get('/organizations/{organization}/meetings/{meeting}', [MeetingController::class, 'show']);
    Route::patch('/organizations/{organization}/meetings/{meeting}', [MeetingController::class, 'update']);
    Route::post('/organizations/{organization}/meetings/{meeting}/respond', [MeetingController::class, 'respond']);
    Route::post('/organizations/{organization}/meetings/{meeting}/cancel', [MeetingController::class, 'cancel']);
    Route::delete('/organizations/{organization}/meetings/{meeting}', [MeetingController::class, 'destroy']);
});
