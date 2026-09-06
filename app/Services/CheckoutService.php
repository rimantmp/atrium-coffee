<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function create(array $data, ?int $userId = null): Order
    {
        $checkoutToken = $data['checkout_token'] ?? null;

        if ($checkoutToken) {
            $existingOrder = Order::query()->where('checkout_token', $checkoutToken)->first();

            if ($existingOrder) {
                return $existingOrder->load('items');
            }
        }

        try {
            return DB::transaction(function () use ($data, $userId, $checkoutToken) {
                $requested = collect($data['items'])->keyBy('menu_id');
                $menus = Menu::query()->whereIn('id', $requested->keys())->where('is_active', true)->lockForUpdate()->get();

                if ($menus->count() !== $requested->count() || $menus->contains(fn (Menu $menu) => ! $menu->is_available)) {
                    throw ValidationException::withMessages(['items' => 'Salah satu menu sudah tidak tersedia. Periksa kembali keranjang Anda.']);
                }

                $subtotal = 0;
                $minutes = 0;
                foreach ($menus as $menu) {
                    $quantity = (int) $requested[$menu->id]['quantity'];
                    $subtotal += (float) $menu->price * $quantity;
                    $minutes += $menu->estimated_prep_minutes * $quantity;
                }

                $order = Order::create([
                    'order_number' => 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                    'tracking_token' => hash('sha256', Str::random(64)),
                    'checkout_token' => $checkoutToken,
                    'created_by_user_id' => $userId,
                    'customer_name' => $data['customer_name'],
                    'customer_phone' => $data['customer_phone'] ?? null,
                    'table_number' => $data['table_number'] ?? null,
                    'order_channel' => $userId ? 'CASHIER' : 'QR',
                    'order_type' => $data['order_type'],
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal,
                    'status' => OrderStatus::PendingPayment,
                    'payment_status' => PaymentStatus::Unpaid,
                    'base_priority' => 3,
                    'estimated_processing_minutes' => max(1, $minutes),
                    'notes' => $data['notes'] ?? null,
                    'ordered_at' => now(),
                ]);

                foreach ($menus as $menu) {
                    $item = $requested[$menu->id];
                    $order->items()->create([
                        'menu_id' => $menu->id,
                        'menu_name_snapshot' => $menu->name,
                        'unit_price' => $menu->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => (float) $menu->price * (int) $item['quantity'],
                        'estimated_prep_minutes' => $menu->estimated_prep_minutes,
                        'notes' => $item['notes'] ?? null,
                    ]);
                }

                DB::table('order_status_histories')->insert([
                    'order_id' => $order->id, 'from_status' => null,
                    'to_status' => OrderStatus::PendingPayment->value,
                    'changed_by_user_id' => $userId, 'created_at' => now(),
                ]);

                return $order->load('items');
            });
        } catch (UniqueConstraintViolationException $exception) {
            if ($checkoutToken && $order = Order::query()->where('checkout_token', $checkoutToken)->first()) {
                return $order->load('items');
            }

            throw $exception;
        }
    }
}
