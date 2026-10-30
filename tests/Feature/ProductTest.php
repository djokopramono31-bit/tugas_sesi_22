<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_create_page_is_available_from_the_product_list(): void
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee(route('products.create'))
            ->assertDontSee('Toko Online')
            ->assertDontSee('pagination')
            ->assertSeeInOrder(['Dashboard', 'Produk']);

        $this->get(route('products.create'))
            ->assertOk()
            ->assertDontSee('Toko Online')
            ->assertSee(route('products.store'))
            ->assertSee('class="form-control ', false)
            ->assertSee('class="form-select ', false)
            ->assertSee('Choose file')
            ->assertSee('No file chosen')
            ->assertSee('aria-describedby="image-file-name"', false)
            ->assertSee('iphone-13-pro.jpg')
            ->assertSee('buku-laravel.svg')
            ->assertSee('keripik-kentang.svg')
            ->assertSee('images/seblak.jpg')
            ->assertSee("productNameInput.addEventListener('input', updateSamplePreview)", false)
            ->assertDontSee('Hajoon0102')
            ->assertSee('Nama Produk')
            ->assertSee('Kategori Produk')
            ->assertSeeInOrder([
                'alt="Keripik kentang"',
                'Gambar Produk',
            ], false)
            ->assertSee('Update Produk')
            ->assertDontSee('Batal')
            ->assertSeeInOrder([
                'id="name"',
                'id="description"',
                'id="stock"',
                'id="price"',
                'id="image"',
                'id="product_category_id"',
            ], false)
            ->assertSee(route('categories.index'))
            ->assertSee(route('categories.create'));
    }

    public function test_product_list_shows_all_products_in_ascending_id_order_without_pagination(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        foreach ([
            ['id' => 30, 'name' => 'Produk C', 'price' => 30000, 'stock' => 3],
            ['id' => 10, 'name' => 'Produk A', 'price' => 10000, 'stock' => 1],
            ['id' => 20, 'name' => 'Produk B', 'price' => 20000, 'stock' => 2],
        ] as $productData) {
            Product::query()->forceCreate([
                ...$productData,
                'product_category_id' => $category->id,
            ]);
        }

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee('pagination')
            ->assertDontSee('Showing')
            ->assertSeeInOrder([
                '<td>1</td>',
                '<td>Produk A</td>',
                '<td>2</td>',
                '<td>Produk B</td>',
                '<td>3</td>',
                '<td>Produk C</td>',
            ], false);
    }

    public function test_product_can_be_deleted_with_confirmation_and_success_message(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);
        $product = Product::create([
            'product_category_id' => $category->id,
            'name' => 'Keyboard',
            'price' => 250000,
            'stock' => 2,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('<dialog id="delete-product-dialog"', false)
            ->assertSee('Apakah Anda yakin ingin menghapus produk ini?')
            ->assertSee('class="d-inline delete-product-form"', false)
            ->assertDontSee('confirm(\'Apakah Anda yakin ingin menghapus produk ini?\')', false);

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success', 'Product deleted successfully');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Product deleted successfully',
                'Tambah Produk',
            ]);
    }

    public function test_dashboard_navigation_is_immediately_before_product_navigation(): void
    {
        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('href="'.route('dashboard').'"', false)
            ->assertSee('href="'.route('products.index').'"', false)
            ->assertSee('href="'.route('categories.index').'"', false)
            ->assertSeeInOrder(['Dashboard', 'Produk', 'Kategori Produk'])
            ->assertSeeInOrder([
                'nav-link fw-bold',
                'href="'.route('categories.index').'"',
                'Kategori Produk',
            ], false);
    }

    public function test_add_product_page_loads_with_available_categories(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->get(route('products.create'))
            ->assertOk()
            ->assertSee(route('products.store'))
            ->assertSee('class="form-control ', false)
            ->assertSee('class="form-select ', false)
            ->assertSee('Choose file')
            ->assertSee('No file chosen')
            ->assertSee('aria-describedby="image-file-name"', false)
            ->assertSee('name="product_category_id"', false)
            ->assertSee('value="'.$category->id.'"', false);
    }

    public function test_edit_product_page_loads_with_product_values_and_can_save_changes(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);
        $product = Product::create([
            'product_category_id' => $category->id,
            'name' => 'Headphone',
            'description' => 'Deskripsi awal',
            'stock' => 5,
            'price' => 250000,
        ]);

        $this->get(route('products.edit', $product))
            ->assertOk()
            ->assertSee('Edit Produk')
            ->assertSee('value="Headphone"', false)
            ->assertSee('Deskripsi awal')
            ->assertSee('value="5"', false)
            ->assertSee('value="250000"', false)
            ->assertSee('action="'.route('products.update', $product).'"', false)
            ->assertSee('name="_method" value="PUT"', false);

        $this->put(route('products.update', $product), [
            'product_category_id' => $category->id,
            'name' => 'Headphone Baru',
            'description' => 'Deskripsi diperbarui',
            'stock' => 8,
            'price' => 300000,
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Headphone Baru',
            'description' => 'Deskripsi diperbarui',
            'stock' => 8,
            'price' => 300000,
        ]);
    }

    public function test_product_can_be_created_with_an_image(): void
    {
        Storage::fake('public');
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Headphone',
            'description' => 'Headphone nirkabel',
            'stock' => 5,
            'price' => 250000,
            'image' => UploadedFile::fake()->image('headphone.png'),
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $product = Product::firstOrFail();
        $this->assertDatabaseHas('products', [
            'name' => 'Headphone',
            'description' => 'Headphone nirkabel',
            'product_category_id' => $category->id,
            'stock' => 5,
            'price' => 250000,
        ]);
        Storage::disk('public')->assertExists($product->image);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Headphone')
            ->assertSee('Headphone nirkabel');
    }

    public function test_electronics_product_uses_the_sample_iphone_image_by_default(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'iPhone',
            'stock' => 3,
            'price' => 15000000,
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'iPhone',
            'image' => 'images/iphone-13-pro.jpg',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee(asset('images/iphone-13-pro.jpg'))
            ->assertDontSee('Foto oleh');
    }

    public function test_non_electronics_product_has_no_sample_image_by_default(): void
    {
        $category = ProductCategory::create(['name' => 'Pakaian']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Kaos',
            'stock' => 3,
            'price' => 50000,
        ])
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Kaos',
            'image' => null,
        ]);
    }

    public function test_book_product_uses_the_laravel_book_image_by_default(): void
    {
        $category = ProductCategory::create(['name' => 'Buku']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Buku Laravel',
            'stock' => 4,
            'price' => 150000,
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Buku Laravel',
            'image' => 'images/buku-laravel.svg',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee(asset('images/buku-laravel.svg'));
    }

    public function test_food_product_uses_the_potato_chips_image_by_default(): void
    {
        $category = ProductCategory::create(['name' => 'Makan']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Keripik Kentang',
            'stock' => 8,
            'price' => 20000,
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Keripik Kentang',
            'image' => 'images/keripik-kentang.svg',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee(asset('images/keripik-kentang.svg'))
            ->assertDontSee('Gunawan Kartapranata');
    }

    public function test_seblak_mercon_product_uses_the_seblak_image_by_default(): void
    {
        $category = ProductCategory::create(['name' => 'Makan']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Seblak Mercon',
            'stock' => 8,
            'price' => 20000,
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Seblak Mercon',
            'image' => 'images/seblak.jpg',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee(asset('images/seblak.jpg'));
    }

    public function test_meja_komputer_product_uses_its_default_image_when_created(): void
    {
        $category = ProductCategory::create(['name' => 'Furniture']);

        $this->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Meja Komputer',
            'stock' => 3,
            'price' => 1500000,
        ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Meja Komputer',
            'image' => 'images/meja-komputer.svg',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('src="'.asset('images/meja-komputer.svg').'"', false)
            ->assertSee('alt="Meja Komputer"', false);
    }

    public function test_existing_meja_komputer_without_an_image_shows_the_default_image(): void
    {
        $category = ProductCategory::create(['name' => 'Furniture']);
        Product::create([
            'product_category_id' => $category->id,
            'name' => 'Meja Komputer',
            'stock' => 3,
            'price' => 1500000,
            'image' => null,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('src="'.asset('images/meja-komputer.svg').'"', false)
            ->assertSee('alt="Meja Komputer"', false);
    }

    public function test_existing_meja_komputer_with_the_electronics_default_shows_the_correct_image(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);
        Product::create([
            'product_category_id' => $category->id,
            'name' => 'Meja Komputer',
            'stock' => 3,
            'price' => 1500000,
            'image' => 'images/iphone-13-pro.jpg',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('src="'.asset('images/meja-komputer.svg').'"', false)
            ->assertDontSee('src="'.asset('images/iphone-13-pro.jpg').'"', false);
    }

    public function test_product_required_fields_must_be_valid(): void
    {
        $this->from(route('products.create'))
            ->post(route('products.store'), [])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors([
                'product_category_id',
                'name',
                'stock',
                'price',
            ]);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_category_must_exist(): void
    {
        $this->from(route('products.create'))
            ->post(route('products.store'), [
                'product_category_id' => 999,
                'name' => 'Headphone',
                'stock' => 5,
                'price' => 250000,
            ])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors('product_category_id');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_image_must_be_an_allowed_image(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->from(route('products.create'))
            ->post(route('products.store'), [
                'product_category_id' => $category->id,
                'name' => 'Headphone',
                'stock' => 5,
                'price' => 250000,
                'image' => UploadedFile::fake()->create('document.txt', 10, 'text/plain'),
            ])
            ->assertRedirect(route('products.create'))
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('products', 0);
    }
}
