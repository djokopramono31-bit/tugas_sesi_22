<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Produk</title>

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
    <div class="container-fluid px-3 px-lg-4">
        <h5 class="mb-0">Tambah Produk</h5>
    </div>
</div>

<div class="container-fluid px-3 px-lg-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nama Produk</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        data-product-name
                        value="{{ old('name') }}"
                        maxlength="255"
                        required
                        autofocus
                    >
                    @error('name')
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
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label fw-bold">Stok Produk</label>
                    <input
                        type="number"
                        class="form-control @error('stock') is-invalid @enderror"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', 0) }}"
                        min="0"
                        required
                    >
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label fw-bold">Harga Produk (Rp)</label>
                    <input
                        type="number"
                        class="form-control @error('price') is-invalid @enderror"
                        id="price"
                        name="price"
                        value="{{ old('price') }}"
                        min="0"
                        step="1"
                        required
                    >
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="food-sample-preview" class="mb-3" hidden>
                    <img
                        id="food-sample-image"
                        src="{{ asset('images/keripik-kentang.svg') }}"
                        alt="Keripik kentang"
                        class="img-thumbnail"
                        style="max-width: 220px; max-height: 220px; object-fit: contain;"
                    >
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Gambar Produk</label>
                    <input
                        type="file"
                        class="visually-hidden @error('image') is-invalid @enderror"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        aria-describedby="image-file-name"
                    >
                    <div class="d-flex align-items-center gap-2">
                        <label for="image" class="btn btn-outline-secondary mb-0">
                            Choose file
                        </label>
                        <span id="image-file-name" aria-live="polite">No file chosen</span>
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                @selected(old('product_category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($categories->isEmpty())
                        <div class="form-text">
                            Tambahkan kategori produk terlebih dahulu.
                            <a href="{{ route('categories.create') }}">
                                Tambah kategori produk
                            </a>
                        </div>
                    @endif
                </div>

                <div id="iphone-sample-preview" class="mb-3" hidden>
                    <p class="form-label mb-2">Contoh gambar untuk kategori produk Elektronik</p>
                    <img
                        src="{{ asset('images/iphone-13-pro.jpg') }}"
                        alt="iPhone 13 Pro"
                        class="img-thumbnail"
                        style="max-width: 220px; max-height: 220px; object-fit: contain;"
                    >
                </div>

                <div id="book-sample-preview" class="mb-3" hidden>
                    <img
                        src="{{ asset('images/buku-laravel.svg') }}"
                        alt="Sampul buku Laravel"
                        class="img-thumbnail"
                        style="max-width: 220px; max-height: 220px; object-fit: contain;"
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Produk
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    #image:focus-visible + .d-flex label {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
    }
</style>

<script>
    const categorySelect = document.getElementById('product_category_id');
    const productNameInput = document.querySelector('[data-product-name]');
    const imageInput = document.getElementById('image');
    const imageFileName = document.getElementById('image-file-name');
    const iphonePreview = document.getElementById('iphone-sample-preview');
    const bookPreview = document.getElementById('book-sample-preview');
    const foodPreview = document.getElementById('food-sample-preview');
    const foodSampleImage = document.getElementById('food-sample-image');

    function updateSamplePreview() {
        const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
        const categoryName = selectedCategory?.textContent
            .trim()
            .toLocaleLowerCase('id') ?? '';
        const productName = productNameInput.value.trim().toLocaleLowerCase('id');
        const hasUploadedImage = imageInput.files.length > 0;

        iphonePreview.hidden = !categoryName.includes('elektronik') || hasUploadedImage;
        bookPreview.hidden = !categoryName.includes('buku') || hasUploadedImage;
        foodPreview.hidden = !categoryName.includes('makan') || hasUploadedImage;
        foodSampleImage.src = productName.includes('seblak')
            ? "{{ asset('images/seblak.jpg') }}"
            : "{{ asset('images/keripik-kentang.svg') }}";
        foodSampleImage.alt = productName.includes('seblak') ? 'Seblak' : 'Keripik kentang';
    }

    categorySelect.addEventListener('change', updateSamplePreview);
    productNameInput.addEventListener('input', updateSamplePreview);
    imageInput.addEventListener('change', () => {
        imageFileName.textContent = imageInput.files.length > 0
            ? imageInput.files[0].name
            : 'No file chosen';
        updateSamplePreview();
    });
    updateSamplePreview();
</script>

</body>
</html>
