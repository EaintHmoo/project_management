<?php

use App\Modules\Dashboard\Http\Controllers\WorkspaceOverviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/workspace/overview', WorkspaceOverviewController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require __DIR__.'/../app/Modules/Auth/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Tenancy/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Teams/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Projects/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Tasks/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Meetings/Presentation/Routes/api.php';
