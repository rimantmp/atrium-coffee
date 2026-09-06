<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('sort_order')->get();
        $menus = Menu::with('category')->orderBy('name')->get();
        $stats = ['menus' => Menu::count(), 'available' => Menu::where('is_available', true)->count(), 'orders' => Order::whereDate('ordered_at', today())->count(), 'revenue' => Order::whereDate('ordered_at', today())->where('payment_status', 'PAID')->sum('total_amount')];
        $settings = DB::table('system_settings')->pluck('value', 'key')->map(fn ($v) => json_decode($v, true));
        $users = User::query()->orderBy('name')->get();

        return view('admin.index', compact('categories', 'menus', 'stats', 'settings', 'users'));
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
