<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $jumlahProduk = Product::count();
        $jumlahKlikProduk = Product::sum('klik');
        $jumlahKategori = ProductCategory::count();
        $produkTerpopuler = Product::query()
            ->where('klik', '>', 0)
            ->orderByDesc('klik')
            ->orderBy('name')
            ->orderBy('id')
            ->limit(5)
            ->get(['id', 'name', 'klik']);

        return view('dashboard', compact(
            'jumlahProduk',
            'jumlahKlikProduk',
            'jumlahKategori',
            'produkTerpopuler',
        ));
    }
}
