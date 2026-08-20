<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Services\ResendTwoFactorService;
use App\Modules\Auth\Application\Services\VerifyTwoFactorService;
use App\Modules\Auth\Domain\DTOs\RequestMetadata;
use App\Modules\Auth\Domain\Models\TwoFactorChallenge;
use App\Modules\Auth\Presentation\Http\Requests\ResendTwoFactorRequest;
use App\Modules\Auth\Presentation\Http\Requests\VerifyTwoFactorRequest;
use App\Modules\Auth\Presentation\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class TwoFactorController extends Controller
{
    public function verify(VerifyTwoFactorRequest $request, VerifyTwoFactorService $service): JsonResponse
    {
        $challenge = TwoFactorChallenge::findOrFail($request->integer('challenge_id'));

        $token = $service->execute(
            $challenge,
            $request->string('code')->toString(),
            new RequestMetadata($request->ip(), $request->userAgent()),
        );

        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($challenge->user),
                'token' => $token->plainTextToken,
            ],
            'message' => 'Signed in successfully.',
        ]);
    }

    public function resend(ResendTwoFactorRequest $request, ResendTwoFactorService $service): JsonResponse
    {
        $challenge = TwoFactorChallenge::findOrFail($request->integer('challenge_id'));

        $newChallenge = $service->execute($challenge->user);

        return response()->json([
            'success' => true,
            'data' => [
                'challenge_id' => $newChallenge->id,
                'expires_at' => $newChallenge->expires_at,
            ],
            'message' => 'A new verification code has been sent to your email.',
        ]);
    }
}
