<?php

namespace App\Modules\Auth\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Domain\Models\SecurityEvent;
use App\Modules\Auth\Presentation\Http\Resources\SecurityEventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function activity(Request $request): JsonResponse
    {
        $events = SecurityEvent::where('user_id', $request->user()->id)
            ->latest('created_at')
            ->limit(25)
            ->get();

        return response()->json([
            'success' => true,
            'data' => SecurityEventResource::collection($events),
            'message' => 'Recent security activity loaded.',
        ]);
    }
}
