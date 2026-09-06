<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SystemHealthTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_public_health_endpoint_returns_only_safe_summary(): void
    {
        $response = $this->getJson(route('health'));

        $response->assertOk()
            ->assertJsonStructure(['status', 'checked_at'])
            ->assertJsonMissingPath('checks')
            ->assertJsonMissingPath('metrics');
    }

    public function test_admin_can_view_operational_alerts(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        DB::table('failed_jobs')->insert([
            'uuid' => fake()->uuid(),
            'connection' => 'database',
            'queue' => 'default',
            'payload' => '{}',
            'exception' => 'Test failure',
            'failed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.operations.index'));

        $response->assertOk()
            ->assertSee('DEGRADED')
            ->assertSee('Failed Jobs');
        $this->assertSame(1, $response->viewData('health')['metrics']['failed_jobs']);
    }

    public function test_non_admin_cannot_view_operational_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'OWNER', 'is_active' => true]);

        $this->actingAs($owner)
            ->get(route('admin.operations.index'))
            ->assertForbidden();
    }
}
