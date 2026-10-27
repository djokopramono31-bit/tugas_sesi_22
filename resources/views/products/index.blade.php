@extends('layouts.app')
@section('content')
<h5 class="mb-3">Daftar Produk</h5>

<div class="card shadow-sm">
<div class="card-body">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm mb-3">Tambah Produk</a>

    <table class="table table-bordered align-middle text-center">
        <thead class="table-success">
            <tr>
                <th>ID</th><th>Nama</th><th>Deskripsi</th><th>Stok</th><th>Harga</th><th>Gambar</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->stock }}</td>
                <td>Rp{{ number_format($product->price,0,',','.') }}</td>
                <td>
                    @php
                        $isMejaKomputer = str_contains(strtolower($product->name), 'meja komputer');
                        $hasDefaultElectronicsImage = $product->image === 'images/iphone-13-pro.jpg';
                        $imagePath = $isMejaKomputer && ($product->image === null || $hasDefaultElectronicsImage)
                            ? 'images/meja-komputer.svg'
                            : $product->image;
                        $imageUrl = $imagePath
                            ? (str_starts_with($imagePath, 'images/')
                                ? asset($imagePath)
                                : asset('storage/'.$imagePath))
                            : null;
                    @endphp
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" class="table-img" alt="{{ $product->name }}">
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm" style="font-size:11px">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          class="d-inline delete-product-form">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" style="font-size:11px">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
</div>

<dialog id="delete-product-dialog" class="border-0 rounded shadow p-4">
    <p class="mb-4">Apakah Anda yakin ingin menghapus produk ini?</p>
    <div class="d-flex justify-content-end gap-2">
        <button type="button" id="cancel-product-delete" class="btn btn-secondary">
            Batal
        </button>
        <button type="button" id="confirm-product-delete" class="btn btn-danger">
            Hapus
        </button>
    </div>
</dialog>

<style>
    #delete-product-dialog::backdrop {
        background: rgb(0 0 0 / 50%);
    }
</style>

<script>
    const deleteProductDialog = document.getElementById('delete-product-dialog');
    const cancelProductDeleteButton = document.getElementById('cancel-product-delete');
    const confirmProductDeleteButton = document.getElementById('confirm-product-delete');
    let productFormToDelete = null;
    let confirmedProductForm = null;

    document.querySelectorAll('.delete-product-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (confirmedProductForm === form) {
                confirmedProductForm = null;

                return;
            }

            event.preventDefault();
            productFormToDelete = form;
            deleteProductDialog.showModal();
        });
    });

    cancelProductDeleteButton.addEventListener('click', () => {
        productFormToDelete = null;
        deleteProductDialog.close();
    });

    confirmProductDeleteButton.addEventListener('click', () => {
        if (!productFormToDelete) {
            return;
        }

        confirmedProductForm = productFormToDelete;
        productFormToDelete.requestSubmit();
        productFormToDelete = null;
        deleteProductDialog.close();
    });

    deleteProductDialog.addEventListener('close', () => {
        productFormToDelete = null;
    });
</script>
@endsection