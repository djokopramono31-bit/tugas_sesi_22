<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::withCount('products')
            ->latest()
            ->paginate(10);

        return view(
            'categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function edit(ProductCategory $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('product_categories', 'name')],
        ], [
            'name.unique' => 'Category name already exists',
        ]);

        ProductCategory::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    public function update(Request $request, ProductCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories', 'name')->ignore($category),
            ],
        ], [
            'name.unique' => 'Category name already exists',
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        DB::transaction(function () use ($category): void {
            $category->products()->delete();
            $category->delete();
        });

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category has successfully deleted');
    }
}
