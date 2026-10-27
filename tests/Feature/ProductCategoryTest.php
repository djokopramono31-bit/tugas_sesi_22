<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_create_page_is_available_from_the_category_list(): void
    {
        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee(route('categories.create'))
            ->assertSeeInOrder(['Dashboard', 'Produk']);

        $this->get(route('categories.create'))
            ->assertOk()
            ->assertSee(route('categories.store'))
            ->assertSee('Nama Kategori Produk');
    }

    public function test_category_can_be_created(): void
    {
        $this->post(route('categories.store'), [
            'name' => 'Elektronik',
        ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('product_categories', [
            'name' => 'Elektronik',
        ]);
    }

    public function test_category_cannot_be_created_with_an_existing_name(): void
    {
        ProductCategory::create(['name' => 'Elektronik']);

        $this->followingRedirects()
            ->from(route('categories.create'))
            ->post(route('categories.store'), [
                'name' => 'Elektronik',
            ])
            ->assertOk()
            ->assertSee('<div class="alert alert-danger" role="alert">', false)
            ->assertSee('Category name already exists');

        $this->assertDatabaseCount('product_categories', 1);
    }

    public function test_category_edit_page_loads_with_existing_name(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee('href="'.route('categories.edit', $category->id).'"', false);

        $this->get(route('categories.edit', $category))
            ->assertOk()
            ->assertSee('Edit Kategori Produk')
            ->assertSee('action="'.route('categories.update', $category).'"', false)
            ->assertSee('name="_method" value="PUT"', false)
            ->assertSee('value="Elektronik"', false);
    }

    public function test_category_can_be_updated(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->put(route('categories.update', $category), [
            'name' => 'Perangkat Elektronik',
        ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategori produk berhasil diperbarui.');

        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'name' => 'Perangkat Elektronik',
        ]);
    }

    public function test_category_cannot_be_updated_to_an_existing_name(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);
        ProductCategory::create(['name' => 'Pakaian']);

        $this->followingRedirects()
            ->from(route('categories.edit', $category))
            ->put(route('categories.update', $category), [
                'name' => 'Pakaian',
            ])
            ->assertOk()
            ->assertSee('<div class="alert alert-danger" role="alert">', false)
            ->assertSee('Category name already exists');

        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'name' => 'Elektronik',
        ]);
    }

    public function test_category_can_be_updated_without_changing_its_existing_name(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->put(route('categories.update', $category), [
            'name' => 'Elektronik',
        ])
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Kategori produk berhasil diperbarui.');

        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'name' => 'Elektronik',
        ]);
    }

    public function test_category_name_is_required(): void
    {
        $this->from(route('categories.create'))
            ->post(route('categories.store'), ['name' => ''])
            ->assertRedirect(route('categories.create'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('product_categories', 0);
    }

    public function test_category_name_is_required_when_updating(): void
    {
        $category = ProductCategory::create(['name' => 'Elektronik']);

        $this->from(route('categories.edit', $category))
            ->put(route('categories.update', $category), ['name' => ''])
            ->assertRedirect(route('categories.edit', $category))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'name' => 'Elektronik',
        ]);
    }

    public function test_category_list_shows_product_count_between_name_and_actions(): void
    {
        $categoryWithProducts = ProductCategory::create(['name' => 'Elektronik']);
        $emptyCategory = ProductCategory::create(['name' => 'Pakaian']);

        Product::create([
            'product_category_id' => $categoryWithProducts->id,
            'name' => 'Headphone',
            'price' => 150000,
            'stock' => 3,
        ]);
        Product::create([
            'product_category_id' => $categoryWithProducts->id,
            'name' => 'Keyboard',
            'price' => 250000,
            'stock' => 2,
        ]);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSeeInOrder([
                '<th>Nama Kategori Produk</th>',
                '<th>Jumlah Produk</th>',
                '<th>Aksi</th>',
                '<td>'.$categoryWithProducts->name.'</td>',
                '<td>2</td>',
                '<td>'.$emptyCategory->name.'</td>',
                '<td>0</td>',
            ], false);
    }

    public function test_category_can_be_deleted_from_the_category_list(): void
    {
        $category = ProductCategory::create(['name' => 'Furniture']);
        $product = Product::create([
            'product_category_id' => $category->id,
            'name' => 'Meja Komputer',
            'price' => 150000,
            'stock' => 3,
        ]);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee('action="'.route('categories.destroy', $category).'"', false)
            ->assertSee('name="_method" value="DELETE"', false)
            ->assertSee('<dialog id="delete-category-dialog"', false)
            ->assertSee('Apakah Anda yakin ingin menghapus kategori ini?')
            ->assertDontSee("confirm('Apakah Anda yakin ingin menghapus kategori ini?')");

        $this->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success', 'Category has successfully deleted');

        $this->assertDatabaseMissing('product_categories', [
            'id' => $category->id,
        ]);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Category has successfully deleted',
                '<th>Nama Kategori Produk</th>',
            ], false);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee($product->name);
    }

    public function test_category_list_numbers_rows_sequentially_instead_of_showing_database_ids(): void
    {
        ProductCategory::query()->forceCreate(['id' => 10, 'name' => 'Elektronik']);
        ProductCategory::query()->forceCreate(['id' => 20, 'name' => 'Pakaian']);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSeeInOrder([
                '<td>1</td>',
                '<td>Elektronik</td>',
                '<td>2</td>',
                '<td>Pakaian</td>',
            ], false);
    }
}
