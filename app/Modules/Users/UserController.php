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
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(ListUsersAction $action): AnonymousResourceCollection
    {
        return UserResource::collection($action->execute());
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): JsonResponse
    {
        $user = $action->execute($request->validated());
        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): UserResource
    {
        return UserResource::make($action->execute($user, $request->validated()));
    }

    public function resetPassword(ResetPasswordRequest $request, User $user, ResetUserPasswordAction $action): JsonResponse
    {
        $action->execute($user, $request->validated());
        return response()->json(['message' => 'Contraseña restablecida.']);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'No puedes eliminar tu propia cuenta.'], 422);
        }
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado.']);
    }
}
