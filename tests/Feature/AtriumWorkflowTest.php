<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\QueueStatus;
use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\QueueService;
use App\Services\SchedulingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AtriumWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function menu(): Menu
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);

        return Menu::create(['category_id' => $category->id, 'sku' => 'TEST-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
    }

    public function test_customer_can_create_pay_and_queue_an_order(): void
    {
        $menu = $this->menu();
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'DINE_IN', 'items' => [['menu_id' => $menu->id, 'quantity' => 2]]]);
        $queue = app(QueueService::class)->payAndEnqueue($order, 'CASH');
        $this->assertSame('A001', $queue->queue_number);
        $this->assertSame(OrderStatus::Queued, $order->fresh()->status);
        $this->assertDatabaseCount('queues', 1);
        $this->assertEquals(50000, $order->total_amount);
    }

    public function test_same_priority_uses_fcfs_and_aging_can_raise_priority(): void
    {
        $menu = $this->menu();
        $service = app(CheckoutService::class);
        $queueService = app(QueueService::class);
        Carbon::setTestNow('2026-08-30 10:00:00');
        $first = $queueService->payAndEnqueue($service->create(['customer_name' => 'First', 'order_type' => 'DINE_IN', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]), 'CASH');
        Carbon::setTestNow('2026-08-30 10:05:00');
        $queueService->payAndEnqueue($service->create(['customer_name' => 'Second', 'order_type' => 'DINE_IN', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]), 'CASH');
        Carbon::setTestNow('2026-08-30 10:11:00');
        $scheduler = app(SchedulingService::class);
        $this->assertSame(2, $scheduler->effectivePriority($first->fresh()));
        $this->assertSame($first->id, $scheduler->orderedWaiting()->first()->id);
        Carbon::setTestNow();
    }

    public function test_kitchen_claim_and_status_transitions_are_synchronized(): void
    {
        $menu = $this->menu();
        $order = app(CheckoutService::class)->create(['customer_name' => 'Dina', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]);
        app(QueueService::class)->payAndEnqueue($order, 'CASH');
        $user = User::factory()->create(['role' => 'KITCHEN']);
        $queue = app(SchedulingService::class)->claimNext($user->id);
        $this->assertSame(QueueStatus::Processing, $queue->status);
        $this->assertSame(OrderStatus::Processing, $order->fresh()->status);
        app(QueueService::class)->markReady($queue, $user->id);
        $this->assertSame(OrderStatus::Ready, $order->fresh()->status);
    }

    public function test_role_middleware_blocks_unauthorized_dashboard(): void
    {
        $owner = User::factory()->create(['role' => 'OWNER']);
        $this->actingAs($owner)->get('/admin')->assertForbidden();
    }
}
