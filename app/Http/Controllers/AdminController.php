<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'menus' => Menu::count(),
            'available' => Menu::where('is_available', true)->count(),
            'orders' => Order::whereDate('ordered_at', today())->count(),
            'revenue' => Order::whereDate('ordered_at', today())->where('payment_status', 'PAID')->sum('total_amount'),
        ];
        $weekStart = today()->subDays(6)->startOfDay();
        $ordersByDate = Order::query()
            ->whereBetween('ordered_at', [$weekStart, now()->endOfDay()])
            ->get(['ordered_at', 'total_amount', 'payment_status'])
            ->groupBy(fn (Order $order): string => $order->ordered_at->toDateString());
        $weeklyTrend = collect(CarbonPeriod::create($weekStart, today()))->map(function ($date) use ($ordersByDate): array {
            $orders = $ordersByDate->get($date->toDateString(), collect());

            return [
                'label' => $date->translatedFormat('D'),
                'orders' => $orders->count(),
                'revenue' => $orders->filter(fn (Order $order): bool => $order->payment_status->value === 'PAID')->sum('total_amount'),
            ];
        });
        $maxWeeklyOrders = max(1, $weeklyTrend->max('orders'));
        $recentOrders = Order::query()->with('items:id,order_id,menu_name_snapshot,quantity')->latest('ordered_at')->limit(6)->get();
        $bestSellers = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.ordered_at', today())
            ->select('order_items.menu_name_snapshot as name', DB::raw('SUM(order_items.quantity) as quantity'))
            ->groupBy('order_items.menu_name_snapshot')
            ->orderByDesc('quantity')
            ->limit(3)
            ->get();
        $maxBestSellerQuantity = max(1, (int) $bestSellers->max('quantity'));
        $statusCounts = Order::query()
            ->whereDate('ordered_at', today())
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        // Warna mengikuti token: selesai = emerald, diproses = coffee, menunggu = amber (DESIGN.md 7.2).
        $statusSummary = [
            ['label' => 'Selesai', 'count' => (int) ($statusCounts['COMPLETED'] ?? 0), 'color' => '#059669'],
            ['label' => 'Diproses', 'count' => (int) (($statusCounts['PROCESSING'] ?? 0) + ($statusCounts['READY'] ?? 0)), 'color' => '#8b563e'],
            ['label' => 'Menunggu', 'count' => (int) (($statusCounts['PENDING_PAYMENT'] ?? 0) + ($statusCounts['WAITING_VERIFICATION'] ?? 0) + ($statusCounts['QUEUED'] ?? 0)), 'color' => '#cd8618'],
        ];

        return view('admin.index', compact('stats', 'weeklyTrend', 'maxWeeklyOrders', 'recentOrders', 'bestSellers', 'maxBestSellerQuantity', 'statusSummary'));
    }

    public function settingsPage(): View
    {
        $settings = DB::table('system_settings')->pluck('value', 'key')->map(fn ($value) => json_decode($value, true));

        return view('admin.settings', compact('settings'));
    }

    public function availability(Menu $menu): RedirectResponse
    {
        $menu->update(['is_available' => ! $menu->is_available]);

        return back()->with('success', 'Ketersediaan menu diperbarui.');
    }

    public function settings(Request $request): RedirectResponse
    {
        $data = $request->validate(['default_priority' => ['required', 'integer', 'between:1,3'], 'minimum_priority' => ['required', 'integer', 'between:1,3'], 'aging_interval_minutes' => ['required', 'integer', 'between:1,120'], 'overdue_minutes' => ['required', 'integer', 'between:5,240']]);
        foreach ($data as $key => $value) {
            DB::table('system_settings')->updateOrInsert(['key' => $key], ['value' => json_encode($value), 'updated_by_user_id' => auth()->id(), 'created_at' => now(), 'updated_at' => now()]);
        }

        return back()->with('success', 'Konfigurasi scheduling disimpan.');
    }
}
