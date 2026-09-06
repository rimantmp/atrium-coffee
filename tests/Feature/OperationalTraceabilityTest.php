<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OperationalTraceabilityTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_every_response_contains_a_generated_request_id(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Request-ID');
        $this->assertMatchesRegularExpression('/^[0-9a-f-]{36}$/', $response->headers->get('X-Request-ID'));
    }

    public function test_admin_can_filter_audit_logs_and_output_is_escaped(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        DB::table('audit_logs')->insert(['user_id' => $admin->id, 'action' => 'MENU_UPDATED', 'entity_type' => 'Menu', 'entity_id' => 1, 'reason' => '<script>alert(1)</script>', 'created_at' => now()]);
        DB::table('audit_logs')->insert(['user_id' => $admin->id, 'action' => 'USER_UPDATED', 'entity_type' => 'User', 'entity_id' => 2, 'created_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.audit-logs.index', ['action' => 'MENU_UPDATED']));

        $response->assertSee('MENU_UPDATED')->assertDontSee('User #2')->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_non_admin_cannot_view_audit_logs(): void
    {
        $owner = User::factory()->create(['role' => 'OWNER', 'is_active' => true]);

        $this->actingAs($owner)->get(route('admin.audit-logs.index'))->assertForbidden();
    }
}
