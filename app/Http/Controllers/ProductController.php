<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    // Menampilkan daftar produk
    public function index(): View
    {
        $products = Product::orderBy('id')->get();

        return view('products.index', compact('products'));
    }

    // Menampilkan detail produk dan menghitung klik
    public function show($id): View
    {
        $product = Product::findOrFail($id);

        // Menambahkan jumlah klik sebanyak 1
        $product->increment('klik');

        return view('products.show', compact('product'));
    }

    // Menampilkan form tambah produk
    public function create(): View
    {
        $categories = ProductCategory::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    // Menyimpan produk baru
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProductData($request);
        $category = ProductCategory::findOrFail($data['product_category_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produk', 'public');
        } else {
            $categoryName = strtolower($category->name);
            $productName = strtolower($data['name']);
            $data['image'] = match (true) {
                str_contains($productName, 'meja komputer') => 'images/meja-komputer.svg',
                str_contains($productName, 'seblak') => 'images/seblak.jpg',
                str_contains($categoryName, 'elektronik') => 'images/iphone-13-pro.jpg',
                str_contains($categoryName, 'buku') => 'images/buku-laravel.svg',
                str_contains($categoryName, 'makan') => 'images/keripik-kentang.svg',
                default => null,
            };
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan form edit produk
    public function edit($id): View
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    // Memperbarui data produk
    public function update(Request $request, $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $data = $this->validatedProductData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produk', 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus produk
    public function destroy($id): RedirectResponse
    {
        Product::findOrFail($id)->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    /** @return array<string, mixed> */
    private function validatedProductData(Request $request): array
    {
        return $request->validate([
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
