<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\QueueService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class OwnerReportTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_owner_report_filters_period_and_excludes_unpaid_orders(): void
    {
        $this->travelTo('2026-09-01 10:00:00');
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
        $checkout = app(CheckoutService::class);
        $paid = $checkout->create(['customer_name' => 'Paid Customer', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 2]]]);
        app(QueueService::class)->payAndEnqueue($paid, 'CASH');
        $checkout->create(['customer_name' => 'Unpaid Customer', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]);
        $owner = User::factory()->create(['role' => 'OWNER', 'is_active' => true]);

        $response = $this->actingAs($owner)->get(route('owner.index', ['from' => '2026-09-01', 'to' => '2026-09-01']));

        $response->assertSee('Rp50.000')->assertSee('Paid Customer')->assertDontSee('Unpaid Customer')->assertSee('2 item');
    }

    public function test_owner_can_export_filtered_paid_transactions_as_csv(): void
    {
        $this->travelTo('2026-09-01 10:00:00');
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]);
        app(QueueService::class)->payAndEnqueue($order, 'CASH');
        $owner = User::factory()->create(['role' => 'OWNER', 'is_active' => true]);

        $response = $this->actingAs($owner)->get(route('owner.export', ['from' => '2026-09-01', 'to' => '2026-09-01']));

        $response->assertDownload('laporan-atrium-2026-09-01-2026-09-01.csv');
        $this->assertStringContainsString('Nadia', $response->streamedContent());
    }

    public function test_report_rejects_invalid_period_and_unauthorized_role(): void
    {
        $owner = User::factory()->create(['role' => 'OWNER', 'is_active' => true]);
        $this->actingAs($owner)->get(route('owner.index', ['from' => '2026-09-02', 'to' => '2026-09-01']))->assertSessionHasErrors('to');
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);
        $this->actingAs($cashier)->get(route('owner.export', ['from' => '2026-09-01', 'to' => '2026-09-01']))->assertForbidden();
    }
}
