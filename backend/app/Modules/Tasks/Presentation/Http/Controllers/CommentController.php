<?php

namespace App\Modules\Tasks\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Application\Services\CreateCommentService;
use App\Modules\Tasks\Application\Services\DeleteCommentService;
use App\Modules\Tasks\Application\Services\UpdateCommentService;
use App\Modules\Tasks\Domain\Models\Comment;
use App\Modules\Tasks\Domain\Models\Task;
use App\Modules\Tasks\Presentation\Http\Requests\CreateCommentRequest;
use App\Modules\Tasks\Presentation\Http\Requests\UpdateCommentRequest;
use App\Modules\Tasks\Presentation\Http\Resources\CommentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $comments = $task->comments()->with(['author', 'replies.author'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => CommentResource::collection($comments),
            'message' => 'Comments loaded.',
        ]);
    }

    public function store(CreateCommentRequest $request, Task $task, CreateCommentService $service): JsonResponse
    {
        $this->authorize('create', [Comment::class, $task]);

        $comment = $service->execute(
            $task,
            $request->user()->id,
            $request->string('body')->toString(),
            $request->input('mentions', []),
            $request->input('parent_id'),
        );

        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment->load('author')),
            'message' => 'Comment added successfully.',
        ], 201);
    }

    public function update(UpdateCommentRequest $request, Task $task, Comment $comment, UpdateCommentService $service): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment = $service->execute($comment, $request->string('body')->toString());

        return response()->json([
            'success' => true,
            'data' => new CommentResource($comment->load('author')),
            'message' => 'Comment updated successfully.',
        ]);
    }

    public function destroy(Request $request, Task $task, Comment $comment, DeleteCommentService $service): JsonResponse
    {
        $this->authorize('delete', $comment);

        $service->execute($comment);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Comment deleted successfully.',
        ]);
    }
}
