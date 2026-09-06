<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class SystemHealthService
{
    /**
     * @return array{
     *     status: 'healthy'|'degraded'|'unhealthy',
     *     checks: array{database: bool, cache: bool},
     *     metrics: array{failed_jobs: ?int, queued_jobs: ?int, stale_notifications: ?int, pending_payments: ?int, overdue_queues: ?int},
     *     checked_at: string
     * }
     */
    public function inspect(): array
    {
        $databaseHealthy = $this->databaseHealthy();
        $cacheHealthy = $this->cacheHealthy();
        $metrics = $databaseHealthy ? $this->operationalMetrics() : $this->unavailableMetrics();
        $hasOperationalAlert = collect($metrics)
            ->filter(fn (?int $value): bool => is_int($value))
            ->contains(fn (int $value): bool => $value > 0);

        return [
            'status' => ! $databaseHealthy || ! $cacheHealthy
                ? 'unhealthy'
                : ($hasOperationalAlert ? 'degraded' : 'healthy'),
            'checks' => [
                'database' => $databaseHealthy,
                'cache' => $cacheHealthy,
            ],
            'metrics' => $metrics,
            'checked_at' => now()->toIso8601String(),
        ];
    }

    private function databaseHealthy(): bool
    {
        try {
            DB::select('select 1');

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function cacheHealthy(): bool
    {
        $key = 'health:'.Str::uuid();

        try {
            Cache::put($key, 'ok', 10);
            $healthy = Cache::get($key) === 'ok';
            Cache::forget($key);

            return $healthy;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @return array{failed_jobs: int, queued_jobs: int, stale_notifications: int, pending_payments: int, overdue_queues: int}
     */
    private function operationalMetrics(): array
    {
        $overdueMinutes = (int) (json_decode(
            DB::table('system_settings')->where('key', 'overdue_minutes')->value('value') ?? '30',
            true,
        ) ?: 30);

        return [
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'queued_jobs' => DB::table('jobs')->count(),
            'stale_notifications' => DB::table('notification_outboxes')
                ->where(function ($query): void {
                    $query->where('status', 'FAILED')
                        ->orWhere(function ($pending): void {
                            $pending->where('status', 'PENDING')
                                ->where('created_at', '<=', now()->subMinutes(5));
                        });
                })->count(),
            'pending_payments' => DB::table('payments')
                ->where('status', 'PENDING')
                ->where('created_at', '<=', now()->subMinutes(15))
                ->count(),
            'overdue_queues' => DB::table('queues')
                ->where('status', 'WAITING')
                ->where('queued_at', '<=', now()->subMinutes($overdueMinutes))
                ->count(),
        ];
    }

    /**
     * @return array{failed_jobs: null, queued_jobs: null, stale_notifications: null, pending_payments: null, overdue_queues: null}
     */
    private function unavailableMetrics(): array
    {
        return [
            'failed_jobs' => null,
            'queued_jobs' => null,
            'stale_notifications' => null,
            'pending_payments' => null,
            'overdue_queues' => null,
        ];
    }
}
