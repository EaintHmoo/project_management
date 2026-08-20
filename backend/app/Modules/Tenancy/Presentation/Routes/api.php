<?php

use App\Modules\Tenancy\Presentation\Http\Controllers\InvitationController;
use App\Modules\Tenancy\Presentation\Http\Controllers\OrganizationController;
use App\Modules\Tenancy\Presentation\Http\Controllers\OrganizationMemberController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::post('/organizations', [OrganizationController::class, 'store']);
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show']);
    Route::patch('/organizations/{organization}', [OrganizationController::class, 'update']);
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switchTo']);

    Route::get('/organizations/{organization}/members', [OrganizationMemberController::class, 'index']);
    Route::patch('/organizations/{organization}/members/{membership}', [OrganizationMemberController::class, 'update']);
    Route::delete('/organizations/{organization}/members/{membership}', [OrganizationMemberController::class, 'destroy']);

    Route::post('/organizations/{organization}/invitations', [InvitationController::class, 'store']);
    Route::get('/invitations', [InvitationController::class, 'index']);
    Route::post('/invitations/{membership}/accept', [InvitationController::class, 'accept']);
    Route::post('/invitations/{membership}/decline', [InvitationController::class, 'decline']);
});
