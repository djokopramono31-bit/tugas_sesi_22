@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4 py-lg-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1">Dashboard</h1>
            </div>
        </div>

        <div class="row g-3 g-lg-4 mb-4">
            <div class="col-12 col-md-4">
                <article class="card h-100 border border-4 border-primary bg-primary-subtle shadow-sm">
                    <div class="card-body p-4">
                        <p class="h4 fw-bold text-center text-decoration-underline mb-3"
                           style="text-underline-offset: 0.35em;">Jumlah Produk</p>
                        <p class="h1 fw-bold text-center mb-2">
                            {{ number_format($jumlahProduk, 0, ',', '.') }}
                        </p>
                        <p class="fs-6 fw-bold text-center text-nowrap mb-0">Total produk yang tersedia di sistem</p>
                    </div>
                </article>
            </div>

            <div class="col-12 col-md-4">
                <article class="card h-100 border border-4 border-success bg-success-subtle shadow-sm">
                    <div class="card-body p-4">
                        <p class="h4 fw-bold text-center text-decoration-underline mb-3"
                           style="text-underline-offset: 0.35em;">Jumlah Klik Produk</p>
                        <p class="display-6 fw-semibold text-center mb-2">
                            {{ number_format($jumlahKlikProduk, 0, ',', '.') }}
                        </p>
                        <p class="small fw-bold text-center text-nowrap mb-0">Total klik produk yang telah dilihat pengguna</p>
                    </div>
                </article>
            </div>

            <div class="col-12 col-md-4">
                <article class="card h-100 border border-4 border-warning bg-warning-subtle shadow-sm">
                    <div class="card-body p-4">
                        <p class="h4 fw-bold text-center text-decoration-underline mb-3"
                           style="text-underline-offset: 0.35em;">Jumlah Kategori Produk</p>
                        <p class="h1 fw-bold text-center mb-2">
                            {{ number_format($jumlahKategori, 0, ',', '.') }}
                        </p>
                        <p class="small fw-bold text-center text-nowrap mb-0">Total kategori produk yang tersedia di sistem</p>
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
