@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4 py-lg-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">{{ $product->name }}</h5>
        </div>
        <div class="card-body">
            @php
                $imageUrl = $product->image
                    ? (str_starts_with($product->image, 'images/')
                        ? asset($product->image)
                        : asset('storage/'.$product->image))
                    : null;
            @endphp

            @if($imageUrl)
                <div class="text-center mb-4">
                    <img src="{{ $imageUrl }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover;" alt="{{ $product->name }}">
                </div>
            @endif

            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Harga:</span>
                    <strong class="text-primary">Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Stok:</span>
                    <span>{{ $product->stock }} unit</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted">Jumlah Dilihat (Klik):</span>
                    <span class="badge bg-success fs-6">{{ $product->klik }} kali</span>
                </li>
                @if($product->description)
                    <li class="list-group-item">
                        <span class="text-muted d-block mb-1">Deskripsi:</span>
                        <p class="mb-0">{{ $product->description }}</p>
                    </li>
                @endif
            </ul>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">Lihat Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection

