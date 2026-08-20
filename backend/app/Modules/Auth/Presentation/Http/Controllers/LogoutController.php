<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Services\LogoutService;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request, LogoutService $service): JsonResponse
    {
        $service->execute($request->user(), new RequestMetadata($request->ip(), $request->userAgent()));

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Signed out successfully.',
        ]);
    }
}
