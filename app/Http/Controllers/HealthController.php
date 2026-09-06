<?php

namespace App\Http\Controllers;

use App\Services\SystemHealthService;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function __invoke(SystemHealthService $healthService): JsonResponse
    {
        $health = $healthService->inspect();

        return response()->json([
            'status' => $health['status'],
            'checked_at' => $health['checked_at'],
        ], $health['status'] === 'unhealthy' ? 503 : 200);
    }
}
