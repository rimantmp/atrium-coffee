<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashierOrderRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\QueueEntry;
use App\Services\CheckoutService;
use App\Services\QueueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['queue', 'payments'])->latest('ordered_at')->paginate(15);
        $categories = Category::with(['menus' => fn ($query) => $query->where('is_active', true)->where('is_available', true)->orderBy('name')])->where('is_active', true)->orderBy('sort_order')->get();
        $stats = ['today' => Order::whereDate('ordered_at', today())->count(), 'waiting' => QueueEntry::where('status', 'WAITING')->count(), 'ready' => QueueEntry::where('status', 'READY')->count(), 'completed' => Order::whereDate('ordered_at', today())->where('status', 'COMPLETED')->count()];

        return view('cashier.index', compact('orders', 'stats', 'categories'));
    }

    public function store(StoreCashierOrderRequest $request, CheckoutService $checkout, QueueService $queueService): RedirectResponse
    {
        $order = $checkout->create($request->validated(), $request->user()->id);
        $queueService->payAndEnqueue($order, $request->string('method')->toString(), $request->user()->id);

        return back()->with('success', "Order walk-in {$order->order_number} berhasil dibuat dan masuk antrean.");
    }

    public function verify(Payment $payment, QueueService $service): RedirectResponse
    {
        $queue = $service->verifyPayment($payment, auth()->id());

        return back()->with('success', "Pembayaran diverifikasi. Antrean {$queue->queue_number} dibuat.");
    }

    public function complete(QueueEntry $queue, QueueService $service): RedirectResponse
    {
        $service->complete($queue, auth()->id());

        return back()->with('success', "Antrean {$queue->queue_number} selesai.");
    }

    public function priority(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate(['priority' => ['required', 'integer', 'between:1,3'], 'reason' => ['required', 'string', 'max:500']]);
        DB::transaction(function () use ($order, $data) {
            $order = Order::lockForUpdate()->findOrFail($order->id);
            $old = $order->base_priority;
            $order->update(['base_priority' => $data['priority']]);
            $order->queue?->update(['base_priority' => $data['priority']]);
            DB::table('priority_change_logs')->insert(['order_id' => $order->id, 'queue_id' => $order->queue?->id, 'old_priority' => $old, 'new_priority' => $data['priority'], 'reason' => $data['reason'], 'changed_by_user_id' => auth()->id(), 'created_at' => now()]);
        });

        return back()->with('success', 'Priority diperbarui dan dicatat.');
    }

    public function cancel(Request $request, Order $order, QueueService $service): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:500']]);
        $service->cancel($order, auth()->id(), $data['reason']);

        return back()->with('success', 'Pesanan dibatalkan dan dicatat dalam audit log.');
    }
}
