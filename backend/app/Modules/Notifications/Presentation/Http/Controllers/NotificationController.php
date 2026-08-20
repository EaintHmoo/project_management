<?php

namespace App\Modules\Notifications\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Notifications\Presentation\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications()->latest()->limit(30)->get();

        return response()->json([
            'success' => true,
            'data' => NotificationResource::collection($notifications),
            'message' => 'Notifications loaded.',
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ['count' => $request->user()->unreadNotifications()->count()],
            'message' => 'Unread count loaded.',
        ]);
    }

    public function markRead(Request $request, string $notification): JsonResponse
    {
        $model = $request->user()->notifications()->findOrFail($notification);
        $model->markAsRead();

        return response()->json([
            'success' => true,
            'data' => new NotificationResource($model->fresh()),
            'message' => 'Notification marked as read.',
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'All notifications marked as read.',
        ]);
    }

    public function destroy(Request $request, string $notification): JsonResponse
    {
        $request->user()->notifications()->findOrFail($notification)->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Notification deleted.',
        ]);
    }
}
