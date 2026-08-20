<?php

use App\Modules\Dashboard\Http\Controllers\WorkspaceOverviewController;
use App\Modules\Tenancy\Presentation\Http\Middleware\ResolveTenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', ResolveTenantContext::class])
    ->get('/workspace/overview', WorkspaceOverviewController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require __DIR__.'/../app/Modules/Auth/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Tenancy/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Teams/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Projects/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Tasks/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Meetings/Presentation/Routes/api.php';
require __DIR__.'/../app/Modules/Notifications/Presentation/Routes/api.php';
