<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(private readonly AuditService $audit) {}

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $category = Category::create($data);
        $this->audit->record('CATEGORY_CREATED', $category, [], $category->only(['name', 'slug', 'sort_order', 'is_active']), null, $request->user()->id);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $oldValues = $category->only(['name', 'slug', 'sort_order', 'is_active']);
        $category->update($data);
        $this->audit->record('CATEGORY_UPDATED', $category, $oldValues, $category->only(['name', 'slug', 'sort_order', 'is_active']), null, $request->user()->id);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->menus()->exists()) {
            return back()->withErrors(['category' => 'Kategori yang masih memiliki menu tidak dapat dihapus.']);
        }

        $category->delete();
        $this->audit->record('CATEGORY_DELETED', $category, $category->only(['name', 'slug']), [], null, request()->user()->id);

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
