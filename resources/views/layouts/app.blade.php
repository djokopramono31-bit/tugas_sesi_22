<!DOCTYPE html>
<html>
<head>
    <title>Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>img.table-img{width:80px; height:80px; object-fit:cover; border-radius:8px;}</style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm mb-4">
  <div class="container">
    <div>
        <a href="/products" class="btn btn-sm btn-primary fw-bold me-2">Dashboard</a>
        <a href="/products" class="btn btn-sm btn-primary fw-bold me-2">Produk</a>
        <a href="/categories" class="btn btn-sm btn-primary fw-bold">Kategori Produk</a>
    </div>
  </div>
</nav>
<main class="container">@yield('content')</main>
</body>
</html>