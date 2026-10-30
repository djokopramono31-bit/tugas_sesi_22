<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Kategori Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid px-4">
        <div class="navbar-nav">
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

<div class="bg-white shadow-sm py-3 px-4 mb-4">
    <h5 class="mb-0">Daftar Kategori Produk</h5>
</div>

<div class="container-fluid px-4">

    <div class="mb-3">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            Daftar Kategori
        </a>
    </div>

    <div class="table-responsive bg-white">
        @if (session('success'))
            <div class="alert alert-success mb-0" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-hover align-middle text-center mb-0">
            <thead class="table-success">
                <tr>
                    <th style="width: 100px;">ID</th>
                    <th>Nama Kategori Produk</th>
                    <th>Jumlah Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('categories.edit', $category->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('categories.destroy', $category->id) }}"
                                      method="POST"
                                      class="mb-0 delete-category-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            Belum ada data kategori produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <span class="text-secondary">
            Menampilkan {{ $categories->firstItem() ?? 0 }}
            sampai {{ $categories->lastItem() ?? 0 }}
            dari {{ $categories->total() }} kategori produk
        </span>

        {{ $categories->links('pagination::bootstrap-5') }}
    </div>

</div>

<dialog id="delete-category-dialog" class="border-0 rounded shadow p-4">
    <p class="mb-4">Apakah Anda yakin ingin menghapus kategori ini?</p>
    <div class="d-flex justify-content-end gap-2">
        <button type="button" id="cancel-category-delete" class="btn btn-secondary">
            Batal
        </button>
        <button type="button" id="confirm-category-delete" class="btn btn-danger">
            Hapus
        </button>
    </div>
</dialog>

<style>
    #delete-category-dialog::backdrop {
        background: rgb(0 0 0 / 50%);
    }
</style>

<script>
    const deleteCategoryDialog = document.getElementById('delete-category-dialog');
    const cancelCategoryDeleteButton = document.getElementById('cancel-category-delete');
    const confirmCategoryDeleteButton = document.getElementById('confirm-category-delete');
    let categoryFormToDelete = null;
    let confirmedCategoryForm = null;

    document.querySelectorAll('.delete-category-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (confirmedCategoryForm === form) {
                confirmedCategoryForm = null;

                return;
            }

            event.preventDefault();
            categoryFormToDelete = form;
            deleteCategoryDialog.showModal();
        });
    });

    cancelCategoryDeleteButton.addEventListener('click', () => {
        categoryFormToDelete = null;
        deleteCategoryDialog.close();
    });

    confirmCategoryDeleteButton.addEventListener('click', () => {
        if (!categoryFormToDelete) {
            return;
        }

        confirmedCategoryForm = categoryFormToDelete;
        categoryFormToDelete.requestSubmit();
        categoryFormToDelete = null;
        deleteCategoryDialog.close();
    });

    deleteCategoryDialog.addEventListener('close', () => {
        categoryFormToDelete = null;
    });
</script>

</body>
</html>
