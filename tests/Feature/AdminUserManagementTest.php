<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_update_and_delete_another_user(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        $this->actingAs($admin)->post(route('admin.users.store'), ['name' => 'Petugas Baru', 'email' => 'petugas@atrium.test', 'role' => 'CASHIER', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123', 'is_active' => '1'])->assertRedirect()->assertSessionHas('success');
        $user = User::where('email', 'petugas@atrium.test')->firstOrFail();
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->actingAs($admin)->put(route('admin.users.update', $user), ['name' => 'Petugas Dapur', 'email' => 'dapur2@atrium.test', 'role' => 'KITCHEN', 'password' => 'passwordbaru', 'password_confirmation' => 'passwordbaru'])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'KITCHEN', 'is_active' => false]);
        $this->actingAs($admin)->delete(route('admin.users.destroy', $user))->assertRedirect()->assertSessionHas('success');
        $this->assertSoftDeleted($user);
    }

    public function test_admin_cannot_demote_deactivate_or_delete_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        $this->actingAs($admin)->put(route('admin.users.update', $admin), ['name' => $admin->name, 'email' => $admin->email, 'role' => 'CASHIER'])->assertSessionHasErrors('user');
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('user');
        $admin->refresh();
        $this->assertSame('ADMIN', $admin->role);
        $this->assertTrue($admin->is_active);
        $this->assertNull($admin->deleted_at);
    }

    public function test_invalid_user_data_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true, 'email' => 'admin@atrium.test']);
        $this->actingAs($admin)->post(route('admin.users.store'), ['name' => 'Invalid', 'email' => 'admin@atrium.test', 'role' => 'SUPERADMIN', 'password' => 'short', 'password_confirmation' => 'short'])->assertSessionHasErrors(['email', 'role', 'password']);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_non_admin_cannot_manage_users(): void
    {
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);
        $this->actingAs($cashier)->post(route('admin.users.store'), ['name' => 'Unauthorized'])->assertForbidden();
        $this->assertDatabaseCount('users',1);
    }
}
