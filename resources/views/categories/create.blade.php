<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kategori Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container px-4">
        <div class="navbar-nav px-3">
            <a class="nav-link fw-bold" href="{{ route('products.index') }}">
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
        <h5 class="mb-0 px-3">Tambah Kategori Produk</h5>
    </div>
</div>

<div class="container px-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    @if ($errors->has('name'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <label for="name" class="form-label">Nama Kategori Produk</label>
                    <input
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        maxlength="255"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Kategori Produk
                </button>
                <a href="{{ route('categories.index') }}"
                   class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
