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
        return CategoryResource::collection($action->execute($request->user()));
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
        $categories = Category::onlyTrashed()
            ->withCount('tasks')
            ->where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();
        return CategoryResource::collection($categories);
    }

    public function restore(Request $request, Category $category): CategoryResource
    {
        $this->ensureOwner($request, $category);
        $category->restore();
        return CategoryResource::make($category->loadCount('tasks'));
    }

    public function forceDelete(Request $request, Category $category): JsonResponse
    {
        $this->ensureOwner($request, $category);
        if ($category->tasks()->withTrashed()->exists()) {
            throw new CategoryHasTasksException();
        }
        $category->forceDelete();
        return response()->json(['message' => 'Categoría eliminada definitivamente.']);
    }

    private function ensureOwner(Request $request, Category $category): void
    {
        abort_unless(
            $request->user()->id === $category->user_id || $request->user()->isAdmin(),
            403,
        );
    }
}
