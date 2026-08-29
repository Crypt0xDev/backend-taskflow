<?php

namespace App\Modules\Comment;

use App\Http\Controllers\Controller;
use App\Modules\Comment\Actions\CreateCommentAction;
use App\Modules\Comment\Actions\ListCommentsAction;
use App\Modules\Comment\Requests\StoreCommentRequest;
use App\Modules\Comment\Resources\CommentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index(ListCommentsAction $action): AnonymousResourceCollection
    {
        return CommentResource::collection($action->execute());
    }

    public function store(StoreCommentRequest $request, CreateCommentAction $action): JsonResponse
    {
        $this->authorize('create', Comment::class);
        $comment = $action->execute($request->user(), $request->validated());
        return CommentResource::make($comment)->response()->setStatusCode(201);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(['message' => 'Comentario eliminado.']);
    }
}
