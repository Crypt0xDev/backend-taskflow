<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = $request->user();
        if (! $user || (! $user->isAdmin() && ! $user->hasPermission($module, $action))) {
            abort(403, 'No tienes permiso para esta acción.');
        }
        return $next($request);
    }
}
