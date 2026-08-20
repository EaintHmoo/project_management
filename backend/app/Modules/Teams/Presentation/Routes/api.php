<?php

use App\Modules\Teams\Presentation\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations/{organization}/teams', [TeamController::class, 'index']);
    Route::post('/organizations/{organization}/teams', [TeamController::class, 'store']);
    Route::get('/organizations/{organization}/teams/{team}', [TeamController::class, 'show']);
    Route::patch('/organizations/{organization}/teams/{team}', [TeamController::class, 'update']);
    Route::delete('/organizations/{organization}/teams/{team}', [TeamController::class, 'destroy']);
});
