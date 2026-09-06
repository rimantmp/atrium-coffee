<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\QueueService;
use App\Services\SchedulingService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class RefundWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function paidOrder(): array
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]);
        app(QueueService::class)->payAndEnqueue($order, 'CASH');

        return [$order->fresh(), $order->payments()->firstOrFail()];
    }

    public function test_cashier_can_refund_a_paid_order_that_is_still_waiting(): void
    {
        [$order, $payment] = $this->paidOrder();
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);

        $this->actingAs($cashier)->post(route('cashier.payments.refund', $payment), ['reason' => 'Pesanan pelanggan dibatalkan'])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('refunds', ['payment_id' => $payment->id, 'amount' => 25000, 'status' => 'SUCCESS', 'processed_by_user_id' => $cashier->id]);
        $this->assertSame(PaymentStatus::Refunded, $order->fresh()->payment_status);
        $this->assertSame(OrderStatus::Cancelled, $order->fresh()->status);
        $this->assertSame('CANCELLED', $order->queue->fresh()->status->value);
    }

    public function test_repeated_refund_request_does_not_create_a_duplicate(): void
    {
        [, $payment] = $this->paidOrder();
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);
        $payload = ['reason' => 'Pesanan pelanggan dibatalkan'];

        $this->actingAs($cashier)->post(route('cashier.payments.refund', $payment), $payload)->assertSessionHas('success');
        $this->actingAs($cashier)->post(route('cashier.payments.refund', $payment), $payload)->assertSessionHas('success');

        $this->assertDatabaseCount('refunds', 1);
    }

    public function test_processing_order_cannot_be_refunded(): void
    {
        [$order, $payment] = $this->paidOrder();
        $kitchen = User::factory()->create(['role' => 'KITCHEN', 'is_active' => true]);
        app(SchedulingService::class)->claimNext($kitchen->id);
        $cashier = User::factory()->create(['role' => 'CASHIER', 'is_active' => true]);

        $this->actingAs($cashier)->post(route('cashier.payments.refund', $payment), ['reason' => 'Terlambat dibatalkan'])->assertSessionHasErrors('refund');

        $this->assertDatabaseCount('refunds', 0);
        $this->assertSame(OrderStatus::Processing, $order->fresh()->status);
    }

    public function test_refund_requires_a_reason_and_cashier_role(): void
    {
        [, $payment] = $this->paidOrder();
        $owner = User::factory()->create(['role' => 'OWNER', 'is_active' => true]);

        $this->actingAs($owner)->post(route('cashier.payments.refund', $payment), ['reason' => 'Valid reason'])->assertForbidden();

        $this->assertDatabaseCount('refunds', 0);
    }
}
