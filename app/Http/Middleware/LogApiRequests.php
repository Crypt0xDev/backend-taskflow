<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/*
| Logs every API request with method, path, status and duration.
| Written to storage/logs/laravel.log (tail it to watch requests).
| 5xx -> error, 4xx -> warning, else -> info.
*/
class LogApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        $ms = (int) round((microtime(true) - $start) * 1000);
        $status = $response->getStatusCode();
        $line = sprintf('%s /%s -> %d (%dms)', $request->method(), $request->path(), $status, $ms);

        if ($status >= 500) {
            Log::error($line);
        } elseif ($status >= 400) {
            Log::warning($line);
        } else {
            Log::info($line);
        }

        return $response;
    }
}
