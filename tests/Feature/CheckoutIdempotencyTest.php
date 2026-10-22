<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutIdempotencyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_menu_form_contains_checkout_token(): void
    {
        $response = $this->get(route('menu'));

        $response->assertOk()
            ->assertSessionHas('checkout_token')
            ->assertSee('name="checkout_token"', false);
    }

    public function test_menu_page_renders_redesigned_storefront_and_available_catalog(): void
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        Menu::create([
            'category_id' => $category->id,
            'sku' => 'LATTE-1',
            'name' => 'Cafe Latte',
            'description' => 'Kopi susu lembut.',
            'price' => 26000,
            'estimated_prep_minutes' => 5,
            'is_available' => true,
            'is_active' => true,
        ]);

        $response = $this->get(route('menu'));

        $response->assertOk()
            ->assertSee('Pesan favoritmu, tanpa antre lama.')
            ->assertSee('Cari menu favoritmu...')
            ->assertSee('Cafe Latte')
            ->assertSee('Rp26.000')
            ->assertSee('Pesanan Anda')
            ->assertSee('data-order-form', false);
    }

    public function test_duplicate_checkout_token_returns_same_order_without_duplicate_items_or_history(): void
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create([
            'category_id' => $category->id,
            'sku' => 'KOPI-1',
            'name' => 'Kopi Susu',
            'price' => 18000,
            'estimated_prep_minutes' => 5,
            'is_available' => true,
            'is_active' => true,
        ]);
        $checkoutToken = (string) Str::uuid();
        $payload = [
            'checkout_token' => $checkoutToken,
            'customer_name' => 'Nadia',
            'order_type' => 'TAKEAWAY',
            'items' => [
                ['menu_id' => $menu->id, 'quantity' => 2],
            ],
        ];

        $firstResponse = $this->post(route('checkout'), $payload);
        $secondResponse = $this->post(route('checkout'), $payload);

        $firstResponse->assertRedirect();
        $secondResponse->assertRedirect($firstResponse->headers->get('Location'));
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('order_status_histories', 1);
        $this->assertDatabaseHas('orders', [
            'checkout_token' => $checkoutToken,
            'customer_name' => 'Nadia',
            'total_amount' => 36000,
        ]);
    }

    public function test_public_checkout_requires_valid_idempotency_token(): void
    {
        $this->post(route('checkout'), [
            'customer_name' => 'Nadia',
            'order_type' => 'TAKEAWAY',
            'items' => [['menu_id' => 1, 'quantity' => 1]],
        ])->assertSessionHasErrors('checkout_token');

        $this->assertDatabaseCount('orders', 0);
    }
}
