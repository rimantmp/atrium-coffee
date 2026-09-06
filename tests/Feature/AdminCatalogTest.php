<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_update_and_delete_an_empty_category(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);

        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Minuman Dingin', 'sort_order' => 2, 'is_active' => '1'])->assertRedirect()->assertSessionHas('success');
        $category = Category::where('name', 'Minuman Dingin')->firstOrFail();
        $this->assertSame('minuman-dingin', $category->slug);

        $this->actingAs($admin)->put(route('admin.categories.update', $category), ['name' => 'Es Kopi', 'sort_order' => 3])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Es Kopi', 'slug' => 'es-kopi', 'is_active' => false]);

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))->assertRedirect()->assertSessionHas('success');
        $this->assertSoftDeleted($category);
    }

    public function test_category_with_a_menu_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'sort_order' => 0, 'is_active' => true]);
        Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))->assertRedirect()->assertSessionHasErrors('category');

        $this->assertNotSoftDeleted($category);
    }

    public function test_admin_can_create_update_and_delete_a_menu_with_an_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'sort_order' => 0, 'is_active' => true]);

        $this->actingAs($admin)->post(route('admin.menus.store'), ['category_id' => $category->id, 'name' => 'Latte', 'description' => 'Kopi susu', 'price' => 25000, 'estimated_prep_minutes' => 5, 'image' => UploadedFile::fake()->image('latte.jpg'), 'is_available' => '1', 'is_active' => '1'])->assertRedirect()->assertSessionHas('success');
        $menu = Menu::where('name', 'Latte')->firstOrFail();
        Storage::disk('public')->assertExists($menu->image_path);

        $this->actingAs($admin)->put(route('admin.menus.update', $menu), ['category_id' => $category->id, 'name' => 'Iced Latte', 'description' => 'Dingin', 'price' => 27000, 'estimated_prep_minutes' => 7, 'is_active' => '1'])->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'name' => 'Iced Latte', 'price' => 27000, 'is_available' => false]);

        $this->actingAs($admin)->delete(route('admin.menus.destroy', $menu))->assertRedirect()->assertSessionHas('success');
        $this->assertSoftDeleted($menu);
    }

    public function test_non_admin_cannot_modify_catalog(): void
    {
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);

        $this->actingAs($cashier)->post(route('admin.categories.store'), ['name' => 'Rahasia', 'sort_order' => 0])->assertForbidden();

        $this->assertDatabaseMissing('categories', ['name' => 'Rahasia']);
    }

    public function test_menu_rejects_invalid_image_and_missing_required_fields(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'ADMIN', 'is_active' => true]);

        $this->actingAs($admin)->from(route('admin.index'))->post(route('admin.menus.store'), ['image' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php')])->assertRedirect(route('admin.index'))->assertSessionHasErrors(['category_id', 'name', 'price', 'estimated_prep_minutes', 'image']);

        $this->assertDatabaseCount('menus', 0);
    }
}
