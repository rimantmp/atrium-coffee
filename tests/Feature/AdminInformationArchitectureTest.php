<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminInformationArchitectureTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_dashboard_only_renders_operational_summary_content(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);

        $response = $this->actingAs($admin)->get(route('admin.index'));

        $response->assertOk()
            ->assertViewIs('admin.index')
            ->assertSee('Tren Order Mingguan')
            ->assertSee('Pesanan Terbaru')
            ->assertDontSee('Tambah Pengguna')
            ->assertDontSee('Konfigurasi Scheduling');
    }

    public function test_admin_can_open_each_modular_management_page(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk()->assertSee('data-modal="create-user"', false);
        $this->actingAs($admin)->get(route('admin.categories.index'))->assertOk()->assertSee('data-modal="create-category"', false);
        $this->actingAs($admin)->get(route('admin.menus.index'))->assertOk()->assertSee('data-modal="create-menu"', false);
        $this->actingAs($admin)->get(route('admin.settings.index'))->assertOk()->assertSee('Konfigurasi Scheduling');
    }

    public function test_menu_grid_filters_by_search_category_and_status(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        $coffee = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'sort_order' => 0, 'is_active' => true]);
        $food = Category::create(['name' => 'Makanan', 'slug' => 'makanan', 'sort_order' => 1, 'is_active' => true]);
        Menu::create(['category_id' => $coffee->id, 'sku' => 'LATTE-1', 'name' => 'Cafe Latte', 'price' => 26000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
        Menu::create(['category_id' => $food->id, 'sku' => 'CRO-1', 'name' => 'Croissant', 'price' => 18000, 'estimated_prep_minutes' => 5, 'is_available' => false, 'is_active' => true]);

        $response = $this->actingAs($admin)->get(route('admin.menus.index', ['search' => 'Latte', 'category' => $coffee->id, 'status' => 'available']));

        $response->assertOk()
            ->assertSee('Cafe Latte')
            ->assertDontSee('Croissant');
    }

    public function test_menu_availability_switch_is_wired_to_its_own_form(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        $coffee = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'sort_order' => 0, 'is_active' => true]);
        $latte = Menu::create(['category_id' => $coffee->id, 'sku' => 'LATTE-1', 'name' => 'Cafe Latte', 'price' => 26000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);

        $html = $this->actingAs($admin)->get(route('admin.menus.index'))->getContent();

        // app.js submits the enclosing form when a [data-quick-toggle] checkbox changes;
        // without the marker the switch moves visually but never persists.
        preg_match_all('/<form[^>]*data-quick-toggle[^>]*>/', $html, $forms);
        $this->assertNotEmpty($forms[0]);

        $target = route('admin.menus.availability', $latte);
        $this->assertTrue(collect($forms[0])->contains(fn (string $form) => str_contains($form, $target)));
    }
}
