<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Category;
use App\Models\Menu;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $categoryId = $request->integer('category');
        $status = $request->string('status')->toString();
        $categories = Category::query()->orderBy('sort_order')->get(['id', 'name']);
        $menus = Menu::query()
            ->with('category:id,name')
            ->when($search, fn ($query, $search) => $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%")))
            ->when($categoryId > 0, fn ($query) => $query->where('category_id', $categoryId))
            ->when($status === 'available', fn ($query) => $query->where('is_available', true)->where('is_active', true))
            ->when($status === 'unavailable', fn ($query) => $query->where(fn ($query) => $query->where('is_available', false)->orWhere('is_active', false)))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menus', compact('categories', 'menus'));
    }

    public function store(StoreMenuRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['sku'] = 'MENU-'.strtoupper(Str::random(8));
        $data['is_available'] = $request->boolean('is_available');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('menus', 'public');
        }

        $menu = Menu::create($data);
        $this->audit->record('MENU_CREATED', $menu, [], $menu->only(['category_id', 'name', 'price', 'is_available', 'is_active']), null, $request->user()->id);

        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['is_available'] = $request->boolean('is_available');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('menus', 'public');
            Storage::disk('public')->delete($menu->image_path);
            $data['image_path'] = $newImagePath;
        }

        $oldValues = $menu->only(['category_id', 'name', 'price', 'is_available', 'is_active', 'image_path']);
        $menu->update($data);
        $this->audit->record('MENU_UPDATED', $menu, $oldValues, $menu->only(['category_id', 'name', 'price', 'is_available', 'is_active', 'image_path']), null, $request->user()->id);

        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();
        $this->audit->record('MENU_DELETED', $menu, $menu->only(['category_id', 'name', 'price']), [], null, request()->user()->id);

        return back()->with('success', 'Menu berhasil dihapus.');
    }
}
