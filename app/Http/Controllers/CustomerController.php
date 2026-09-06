<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\QueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function menu(Request $request): View
    {
        if (! $request->session()->has('checkout_token')) {
            $request->session()->put('checkout_token', (string) Str::uuid());
        }

        $categories = Category::query()->with(['menus' => fn ($q) => $q->where('is_active', true)->orderBy('name')])->where('is_active', true)->orderBy('sort_order')->get();

        return view('customer.menu', compact('categories'));
    }

    public function checkout(Request $request, CheckoutService $checkout): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'], 'customer_phone' => ['nullable', 'string', 'max:25'],
            'table_number' => ['nullable', 'string', 'max:30'], 'order_type' => ['required', 'in:DINE_IN,TAKEAWAY'],
            'notes' => ['nullable', 'string', 'max:500'], 'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'distinct'], 'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],
            'checkout_token' => ['required', 'uuid'],
        ]);
        $order = $checkout->create($data);
        $request->session()->forget('checkout_token');

        return redirect()->route('orders.track', $order->tracking_token)->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function track(string $token): View
    {
        $order = Order::with(['items', 'queue'])->where('tracking_token', $token)->firstOrFail();

        return view('customer.track', compact('order'));
    }

    public function status(string $token): JsonResponse
    {
        $order = Order::with('queue')->where('tracking_token', $token)->firstOrFail();

        return response()->json(['data' => ['status' => $order->status->value, 'label' => $order->status->label(), 'queue_number' => $order->queue?->queue_number, 'estimated_wait_minutes' => $order->queue?->estimated_wait_minutes, 'updated_at' => $order->updated_at->toIso8601String()]]);
    }

    public function pay(string $token, Request $request, QueueService $queueService): RedirectResponse
    {
        $data = $request->validate(['method' => ['required', 'in:CASH,QRIS']]);
        $order = Order::where('tracking_token', $token)->firstOrFail();
        $queueService->requestPayment($order, $data['method']);

        return back()->with('success', 'Pembayaran diajukan dan menunggu verifikasi kasir.');
    }
}
