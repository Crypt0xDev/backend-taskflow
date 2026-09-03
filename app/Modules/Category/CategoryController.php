<?php

namespace App\Modules\Category;

use App\Http\Controllers\Controller;
use App\Modules\Category\Actions\CreateCategoryAction;
use App\Modules\Category\Exceptions\CategoryHasTasksException;
use App\Modules\Category\Actions\ListCategoriesAction;
use App\Modules\Category\Actions\UpdateCategoryAction;
use App\Modules\Category\Requests\StoreCategoryRequest;
use App\Modules\Category\Requests\UpdateCategoryRequest;
use App\Modules\Category\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesAction $action): AnonymousResourceCollection
    {
        return CategoryResource::collection($action->execute($request->user(), $request->boolean('all')));
    }

    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): JsonResponse
    {
        $this->authorize('create', Category::class);
        $category = $action->execute($request->user(), $request->validated());
        return CategoryResource::make($category)->response()->setStatusCode(201);
    }

    public function show(Category $category): CategoryResource
    {
        $this->authorize('view', $category);
        return CategoryResource::make($category->loadCount('tasks'));
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): CategoryResource
    {
        $this->authorize('update', $category);
        return CategoryResource::make($action->execute($category, $request->validated()));
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json(['message' => 'Categoría enviada a la papelera.']);
    }

    public function trashed(Request $request): AnonymousResourceCollection
    {
        $all = $request->boolean('all') && $request->user()->isAdmin();
        $categories = Category::onlyTrashed()
            ->withCount('tasks')
            ->when(! $all, fn($query) => $query->where('user_id', $request->user()->id))
            ->orderBy('name')
            ->paginate(20);
        return CategoryResource::collection($categories);
    }

    public function restore(Category $category): CategoryResource
    {
        $this->authorize('restore', $category);
        $category->restore();
        return CategoryResource::make($category->loadCount('tasks'));
    }

    public function forceDelete(Category $category): JsonResponse
    {
        $this->authorize('forceDelete', $category);
        if ($category->tasks()->withTrashed()->exists()) {
            throw new CategoryHasTasksException();
        }
        $category->forceDelete();
        return response()->json(['message' => 'Categoría eliminada definitivamente.']);
    }
}
