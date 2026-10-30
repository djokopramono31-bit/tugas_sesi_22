<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container px-4">
        <div class="navbar-nav px-3">
            <a class="nav-link fw-bold" href="{{ route('dashboard') }}">
                Dashboard
            </a>
            <a class="nav-link fw-bold" href="{{ route('products.index') }}">
                Produk
            </a>
            <a class="nav-link fw-bold" href="{{ route('categories.index') }}">
                Kategori Produk
            </a>
        </div>
    </div>
</nav>

<div class="bg-white shadow-sm py-3 mb-4">
    <div class="container px-4">
        <h5 class="mb-0 px-3">Edit Produk</h5>
    </div>
</div>

<div class="container px-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update', $product) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nama Produk</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        maxlength="255"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="product_category_id" class="form-label fw-bold">
                        Kategori Produk
                    </label>
                    <select
                        class="form-select @error('product_category_id') is-invalid @enderror"
                        id="product_category_id"
                        name="product_category_id"
                        required
                    >
                        <option value="">Pilih kategori produk</option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('product_category_id', $product->product_category_id) == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Deskripsi Produk</label>
                    <textarea
                        class="form-control @error('description') is-invalid @enderror"
                        id="description"
                        name="description"
                        rows="3"
                    >{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label fw-bold">Stok Produk</label>
                        <input
                            type="number"
                            class="form-control @error('stock') is-invalid @enderror"
                            id="stock"
                            name="stock"
                            value="{{ old('stock', $product->stock) }}"
                            min="0"
                            required
                        >
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label fw-bold">Harga Produk (Rp)</label>
                        <input
                            type="number"
                            class="form-control @error('price') is-invalid @enderror"
                            id="price"
                            name="price"
                            value="{{ old('price', $product->price) }}"
                            min="0"
                            step="1"
                            required
                        >
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Gambar Produk</label>
                    @if ($product->image)
                        @php
                            $imageUrl = str_starts_with($product->image, 'images/')
                                ? asset($product->image)
                                : asset('storage/'.$product->image);
                        @endphp
                        <div class="mb-2">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                 class="img-thumbnail"
                                 style="max-width: 220px; max-height: 220px; object-fit: contain;">
                        </div>
                    @endif
                    <input
                        type="file"
                        class="form-control @error('image') is-invalid @enderror"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Produk
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
