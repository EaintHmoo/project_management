<?php

use App\Modules\Tasks\Presentation\Http\Controllers\CommentController;
use App\Modules\Tasks\Presentation\Http\Controllers\LabelController;
use App\Modules\Tasks\Presentation\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations/{organization}/labels', [LabelController::class, 'index']);
    Route::post('/organizations/{organization}/labels', [LabelController::class, 'store']);
    Route::delete('/organizations/{organization}/labels/{label}', [LabelController::class, 'destroy']);

    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show']);
    Route::patch('/projects/{project}/tasks/{task}', [TaskController::class, 'update']);
    Route::post('/projects/{project}/tasks/{task}/move', [TaskController::class, 'move']);
    Route::post('/projects/{project}/tasks/{task}/assign', [TaskController::class, 'assign']);
    Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy']);

    Route::get('/tasks/{task}/comments', [CommentController::class, 'index']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store']);
    Route::patch('/tasks/{task}/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/tasks/{task}/comments/{comment}', [CommentController::class, 'destroy']);
});
