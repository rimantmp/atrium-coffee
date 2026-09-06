<?php

namespace Tests\Feature;

use App\Jobs\ProcessNotificationOutbox;
use App\Models\Category;
use App\Models\Menu;
use App\Models\NotificationOutbox;
use App\Models\User;
use App\Services\CheckoutService;
use App\Services\QueueService;
use App\Services\SchedulingService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationOutboxTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_ready_transition_creates_one_outbox_and_dispatches_worker_after_commit(): void
    {
        Queue::fake([ProcessNotificationOutbox::class]);
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]);
        $queue = app(QueueService::class)->payAndEnqueue($order, 'CASH');
        $kitchen = User::factory()->create(['role' => 'KITCHEN', 'is_active' => true]);
        app(SchedulingService::class)->claimNext($kitchen->id);

        app(QueueService::class)->markReady($queue, $kitchen->id);

        $this->assertDatabaseHas('notification_outboxes', ['order_id' => $order->id, 'event_type' => 'ORDER_READY', 'status' => 'PENDING', 'attempts' => 0]);
        Queue::assertPushed(ProcessNotificationOutbox::class, fn (ProcessNotificationOutbox $job) => $job->outboxId === 1);
    }

    public function test_outbox_worker_marks_notification_sent_idempotently(): void
    {
        $category = Category::create(['name' => 'Kopi', 'slug' => 'kopi', 'is_active' => true]);
        $menu = Menu::create(['category_id' => $category->id, 'sku' => 'KOPI-1', 'name' => 'Latte', 'price' => 25000, 'estimated_prep_minutes' => 5, 'is_available' => true, 'is_active' => true]);
        $order = app(CheckoutService::class)->create(['customer_name' => 'Nadia', 'order_type' => 'TAKEAWAY', 'items' => [['menu_id' => $menu->id, 'quantity' => 1]]]);
        $outbox = NotificationOutbox::create(['event_key' => "order:{$order->id}:ORDER_READY", 'order_id' => $order->id, 'event_type' => 'ORDER_READY', 'channel' => 'WEB', 'payload' => ['status' => 'READY'], 'status' => 'PENDING']);
        $job = new ProcessNotificationOutbox($outbox->id);

        $job->handle();
        $job->handle();

        $outbox->refresh();
        $this->assertSame('SENT', $outbox->status);
        $this->assertSame(1, $outbox->attempts);
        $this->assertNotNull($outbox->processed_at);
    }
}
