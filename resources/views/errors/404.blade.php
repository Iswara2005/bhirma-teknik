@extends('layouts.app')
@section('title', 'Halaman tidak ditemukan | ' . $company['name'])

@section('content')
<section class="container py-5 text-center">
    <div class="py-5">
        <p class="eyebrow mb-2">Kesalahan 404</p>
        <h1 class="display-5 fw-bold mb-3">Halaman tidak ditemukan</h1>
        <p class="text-secondary mb-4 col-lg-6 mx-auto">Alamat yang Anda buka tidak tersedia atau sudah dipindahkan. Silakan kembali ke beranda atau lihat bidang usaha kami.</p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="{{ route('home') }}" class="btn btn-danger btn-lg rounded-0 px-4 fw-semibold">Kembali ke Beranda</a>
            <a href="{{ route('services') }}" class="btn btn-outline-dark btn-lg rounded-0 px-4 fw-semibold">Lihat Bidang Usaha</a>
        </div>
    </div>
</section>
@endsection