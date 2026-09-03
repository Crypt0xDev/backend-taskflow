<?php

namespace App\Modules\Tag;

use App\Http\Controllers\Controller;
use App\Modules\Tag\Actions\CreateTagAction;
use App\Modules\Tag\Actions\ListTagsAction;
use App\Modules\Tag\Actions\UpdateTagAction;
use App\Modules\Tag\Exceptions\TagInUseException;
use App\Modules\Tag\Requests\StoreTagRequest;
use App\Modules\Tag\Requests\UpdateTagRequest;
use App\Modules\Tag\Resources\TagResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function index(Request $request, ListTagsAction $action): AnonymousResourceCollection
    {
        return TagResource::collection($action->execute($request->user(), $request->boolean('all')));
    }

    public function store(StoreTagRequest $request, CreateTagAction $action): JsonResponse
    {
        $this->authorize('create', Tag::class);
        $tag = $action->execute($request->user(), $request->validated());
        return TagResource::make($tag)->response()->setStatusCode(201);
    }

    public function show(Tag $tag): TagResource
    {
        $this->authorize('view', $tag);
        return TagResource::make($tag->loadCount('tasks'));
    }

    public function update(UpdateTagRequest $request, Tag $tag, UpdateTagAction $action): TagResource
    {
        $this->authorize('update', $tag);
        return TagResource::make($action->execute($tag, $request->validated()));
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $this->authorize('delete', $tag);
        $tag->delete();
        return response()->json(['message' => 'Etiqueta enviada a la papelera.']);
    }

    public function trashed(Request $request): AnonymousResourceCollection
    {
        $all = $request->boolean('all') && $request->user()->isAdmin();
        $tags = Tag::onlyTrashed()
            ->withCount('tasks')
            ->when(! $all, fn($query) => $query->where('user_id', $request->user()->id))
            ->orderBy('name')
            ->paginate(20);
        return TagResource::collection($tags);
    }

    public function restore(Tag $tag): TagResource
    {
        $this->authorize('restore', $tag);
        $tag->restore();
        return TagResource::make($tag->loadCount('tasks'));
    }

    public function forceDelete(Tag $tag): JsonResponse
    {
        $this->authorize('forceDelete', $tag);
        if ($tag->tasks()->withTrashed()->exists()) {
            throw new TagInUseException();
        }

        $tag->forceDelete();
        return response()->json(['message' => 'Etiqueta eliminada definitivamente.']);
    }
}
