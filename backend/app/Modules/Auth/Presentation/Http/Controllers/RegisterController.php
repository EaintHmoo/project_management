<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Services\RegisterUserService;
use App\Modules\Auth\Domain\DTOs\RegisterUserData;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Presentation\Http\Requests\RegisterRequest;
use App\Modules\Auth\Presentation\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterUserService $service): JsonResponse
    {
        $user = $service->execute(
            new RegisterUserData(
                name: $request->string('name')->toString(),
                email: $request->string('email')->toString(),
                password: $request->string('password')->toString(),
            ),
            new RequestMetadata($request->ip(), $request->userAgent()),
        );

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
            'message' => 'Registration successful. Please check your email to verify your account.',
        ], 201);
    }
}
