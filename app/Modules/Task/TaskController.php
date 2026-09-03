<?php

namespace App\Modules\Task;

use App\Http\Controllers\Controller;
use App\Modules\Task\Actions\CreateTaskAction;
use App\Modules\Task\Actions\ListTasksAction;
use App\Modules\Task\Actions\ListTrashedTasksAction;
use App\Modules\Task\Actions\UpdateTaskAction;
use App\Modules\Task\Requests\StoreTaskRequest;
use App\Modules\Task\Requests\UpdateTaskRequest;
use App\Modules\Task\Resources\TaskResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function index(Request $request, ListTasksAction $action): AnonymousResourceCollection
    {
        $q = trim((string) $request->query('q', ''));
        $tasks = $action->execute($request->user(), $q !== '' ? $q : null, $request->boolean('all'));
        return TaskResource::collection($tasks);
    }

    public function trashed(Request $request, ListTrashedTasksAction $action): AnonymousResourceCollection
    {
        return TaskResource::collection($action->execute($request->user(), $request->boolean('all')));
    }

    public function store(StoreTaskRequest $request, CreateTaskAction $action): JsonResponse
    {
        $this->authorize('create', Task::class);
        $task = $action->execute($request->user(), $request->validated());
        return TaskResource::make($task)->response()->setStatusCode(201);
    }

    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);
        return TaskResource::make($task->load(['category', 'tags']));
    }

    public function update(UpdateTaskRequest $request, Task $task, UpdateTaskAction $action): TaskResource
    {
        $this->authorize('update', $task);
        return TaskResource::make($action->execute($task, $request->validated()));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(['message' => 'Tarea enviada a la papelera.']);
    }

    public function restore(Task $task): TaskResource
    {
        $this->authorize('restore', $task);
        $task->restore();
        return TaskResource::make($task->load(['category', 'tags']));
    }

    public function forceDelete(Task $task): JsonResponse
    {
        $this->authorize('forceDelete', $task);
        $task->forceDelete();
        return response()->json(['message' => 'Tarea eliminada definitivamente.']);
    }
}
