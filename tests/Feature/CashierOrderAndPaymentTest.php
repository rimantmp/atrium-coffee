<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CashierOrderAndPaymentTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function menu(): Menu
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);

        return Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
    }

    public function test_customer_payment_waits_for_cashier_verification_before_queueing(): void
    {
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'DINE_IN', 'items' => [['menu_id' => $this->menu()->id, 'quantity' => 1]]]);

        $this->post(route('orders.pay', $order->tracking_token), ['method' => 'QRIS'])->assertRedirect()->assertSessionHas('success');

        $order->refresh();
        $this->assertSame(OrderStatus::WaitingVerification, $order->status);
        $this->assertSame(PaymentStatus::Pending, $order->payment_status);
        $this->assertDatabaseCount('queues', 0);
        $this->assertDatabaseHas('payments', ['order_id' => $order->id, 'status' => 'PENDING']);
    }

    public function test_cashier_can_verify_pending_payment_once(): void
    {
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'DINE_IN', 'items' => [['menu_id' => $this->menu()->id, 'quantity' => 1]]]);
        $this->post(route('orders.pay', $order->tracking_token), ['method' => 'CASH']);
        $payment = $order->payments()->firstOrFail();
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);

        $this->actingAs($cashier)->post(route('cashier.payments.verify', $payment))->assertRedirect()->assertSessionHas('success');
        $this->actingAs($cashier)->post(route('cashier.payments.verify', $payment))->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseCount('queues', 1);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'PAID', 'verified_by_user_id' => $cashier->id]);
        $this->assertSame(OrderStatus::Queued, $order->fresh()->status);
    }

    public function test_cashier_can_create_a_paid_walk_in_order(): void
    {
        $menu = $this->menu();
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);

        $this->actingAs($cashier)->post(route('cashier.orders.store'), ['customer_name' => 'Walk In', 'order_type' => 'TAKEAWAY', 'method' => 'CASH', 'items' => [['menu_id' => $menu->id, 'quantity' => 2]]])->assertRedirect()->assertSessionHas('success');

        $order = Order::where('customer_name', 'Walk In')->firstOrFail();
        $this->assertSame('CASHIER', $order->order_channel);
        $this->assertSame(OrderStatus::Queued, $order->status);
        $this->assertSame('50000.00', $order->total_amount);
        $this->assertNotNull($order->queue);
    }
}
