<?php

namespace App\Modules\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Users\Actions\CreateUserAction;
use App\Modules\Users\Actions\ListUsersAction;
use App\Modules\Users\Actions\ResetUserPasswordAction;
use App\Modules\Users\Actions\UpdateUserAction;
use App\Modules\Users\Requests\ResetPasswordRequest;
use App\Modules\Users\Requests\StoreUserRequest;
use App\Modules\Users\Requests\UpdateUserRequest;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(ListUsersAction $action): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return UserResource::collection($action->execute());
    }

    public function show(User $user): UserResource
    {
        $this->authorize('view', $user);
        return UserResource::make($user->load('role.permissions'));
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): JsonResponse
    {
        $this->authorize('create', User::class);
        $user = $action->execute($request->validated());
        return UserResource::make($user->load('role.permissions'))->response()->setStatusCode(201);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): UserResource
    {
        $this->authorize('update', $user);
        $updated = $action->execute($user, $request->validated());
        return UserResource::make($updated->load('role.permissions'));
    }

    public function resetPassword(ResetPasswordRequest $request, User $user, ResetUserPasswordAction $action): JsonResponse
    {
        $this->authorize('resetPassword', $user);
        $action->execute($user, $request->validated());
        return response()->json(['message' => 'Contraseña restablecida.']);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado.']);
    }
}
