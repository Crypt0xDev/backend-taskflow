<?php

namespace App\Modules\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Actions\LoginUserAction;
use App\Modules\Auth\Actions\RegisterUserAction;
use App\Modules\Auth\Actions\UpdatePasswordAction;
use App\Modules\Auth\Actions\UpdateProfileAction;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Requests\UpdatePasswordRequest;
use App\Modules\Auth\Requests\UpdateProfileRequest;
use App\Modules\Users\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());
        return response()->json([
            'user' => new UserResource($result['user']->load('role.permissions')),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());
        return response()->json([
            'user' => new UserResource($result['user']->load('role.permissions')),
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada.']);
    }


    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load('role.permissions'));
    }

    public function updateProfile(UpdateProfileRequest $request, UpdateProfileAction $action): UserResource
    {
        $user = $action->execute($request->user(), $request->validated());
        return new UserResource($user->load('role.permissions'));
    }

    public function updatePassword(UpdatePasswordRequest $request, UpdatePasswordAction $action): JsonResponse
    {
        $action->execute($request->user(), $request->validated());
        return response()->json(['message' => 'Contraseña actualizada.']);
    }
}
