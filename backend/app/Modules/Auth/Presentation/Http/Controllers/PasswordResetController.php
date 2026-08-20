<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Services\ForgotPasswordService;
use App\Modules\Auth\Application\Services\ResetPasswordService;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Presentation\Http\Requests\ForgotPasswordRequest;
use App\Modules\Auth\Presentation\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function forgot(ForgotPasswordRequest $request, ForgotPasswordService $service): JsonResponse
    {
        $status = $service->execute($request->string('email')->toString());

        return response()->json([
            'success' => $status === Password::RESET_LINK_SENT,
            'data' => null,
            'message' => __($status),
        ]);
    }

    public function reset(ResetPasswordRequest $request, ResetPasswordService $service): JsonResponse
    {
        $status = $service->execute(
            $request->string('email')->toString(),
            $request->string('token')->toString(),
            $request->string('password')->toString(),
            new RequestMetadata($request->ip(), $request->userAgent()),
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'success' => false,
                'message' => __($status),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Password reset successfully.',
        ]);
    }
}
