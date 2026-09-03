<?php

namespace App\Modules\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePasswordHasBeenChanged
{
    private const ALLOWED = [
        ['GET', 'api/v1/me'],
        ['PUT', 'api/v1/me/password'],
        ['POST', 'api/v1/logout'],
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! $user->must_change_password) {
            return $next($request);
        }

        foreach (self::ALLOWED as [$method, $path]) {
            if ($request->isMethod($method) && $request->is($path)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Debes cambiar tu contraseña antes de continuar.',
            'must_change_password' => true,
        ], 423);
    }
}
