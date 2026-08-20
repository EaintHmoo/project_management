<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Services\LoginUserService;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Presentation\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginUserService $service): JsonResponse
    {
        $challenge = $service->execute(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            new RequestMetadata($request->ip(), $request->userAgent()),
        );

        return response()->json([
            'success' => true,
            'data' => [
                'challenge_id' => $challenge->id,
                'expires_at' => $challenge->expires_at,
            ],
            'message' => 'A verification code has been sent to your email.',
        ]);
    }
}
