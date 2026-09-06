<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AssignRequestId
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = Str::uuid()->toString();
        $startedAt = hrtime(true);
        Context::add('request_id', $requestId);
        Log::withContext(['request_id' => $requestId]);

        $response = $next($request);
        $response->headers->set('X-Request-ID', $requestId);
        Log::info('http.request.completed', ['method' => $request->method(), 'path' => $request->path(), 'status' => $response->getStatusCode(), 'duration_ms' => round((hrtime(true) - $startedAt) / 1_000_000, 2), 'user_id' => $request->user()?->id]);

        return $response;
    }
}
