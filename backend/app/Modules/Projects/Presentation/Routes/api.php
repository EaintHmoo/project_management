<?php

use App\Modules\Projects\Presentation\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations/{organization}/projects', [ProjectController::class, 'index']);
    Route::post('/organizations/{organization}/projects', [ProjectController::class, 'store']);
    Route::get('/organizations/{organization}/projects/{project}', [ProjectController::class, 'show']);
    Route::patch('/organizations/{organization}/projects/{project}', [ProjectController::class, 'update']);
    Route::post('/organizations/{organization}/projects/{project}/archive', [ProjectController::class, 'archive']);
    Route::delete('/organizations/{organization}/projects/{project}', [ProjectController::class, 'destroy']);
});
