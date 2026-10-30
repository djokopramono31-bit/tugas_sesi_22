<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_the_dashboard(): void
    {
        $this->get('/')
            ->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_does_not_show_an_empty_click_message_when_no_products_have_clicks(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Belum ada klik produk untuk ditampilkan.');
    }

    public function test_dashboard_displays_store_totals_and_products_ordered_by_clicks(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        foreach ([
            ['name' => 'Produk A', 'klik' => 12],
            ['name' => 'Produk B', 'klik' => 5],
            ['name' => 'Produk tanpa klik', 'klik' => 0],
        ] as $productData) {
            Product::query()->forceCreate([
                ...$productData,
                'product_category_id' => $category->id,
                'price' => 10000,
                'stock' => 2,
            ]);
        }

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Tambah produk')
            ->assertDontSee('Lihat semua produk')
            ->assertSee('nav-link fw-bold', false)
            ->assertSeeInOrder([
                'href="'.route('products.index').'"',
                'Produk</a>',
                'href="'.route('categories.index').'"',
                'Kategori Produk</a>',
            ], false)
            ->assertSeeInOrder([
                'Jumlah Produk',
                '3',
                'Jumlah Klik Produk',
                '17',
                'Jumlah Kategori Produk',
                '1',
            ])
            ->assertSee('Total produk yang tersedia di sistem')
            ->assertSee('Total kategori produk yang tersedia di sistem')
            ->assertSee('small fw-bold text-center text-nowrap', false)
            ->assertSee('border-4 border-success bg-success-subtle', false)
            ->assertSee('border-4 border-warning bg-warning-subtle', false)
            ->assertSee('Total klik produk yang telah dilihat pengguna')
            ->assertSee('small fw-bold text-center text-nowrap', false)
            ->assertDontSee('Kelola produk')
            ->assertDontSee('Kelola kategori')
            ->assertDontSee('Ringkasan toko')
            ->assertDontSee('Pantau produk dan aktivitas toko Anda.')
            ->assertDontSee('Produk paling sering dilihat')
            ->assertSee('text-nowrap', false)
            ->assertDontSee('Belum ada klik produk untuk ditampilkan.')
            ->assertDontSee('Produk tanpa klik');
    }
}
