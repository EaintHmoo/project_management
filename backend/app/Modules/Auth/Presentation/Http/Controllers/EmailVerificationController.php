<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Auth\Application\Services\VerifyEmailService;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, int $id, string $hash, VerifyEmailService $service): JsonResponse
    {
        if (! URL::hasValidSignature($request)) {
            return response()->json([
                'success' => false,
                'message' => 'This verification link is invalid or has expired.',
            ], 403);
        }

        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'success' => false,
                'message' => 'This verification link is invalid.',
            ], 403);
        }

        $service->execute($user, new RequestMetadata($request->ip(), $request->userAgent()));

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Email verified successfully.',
        ]);
    }

    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Email already verified.',
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Verification link sent.',
        ]);
    }
}
