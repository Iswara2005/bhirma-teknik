@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
@php $imagePct = $slotCount ? round($customImageCount / $slotCount * 100) : 0; @endphp

{{-- ===== Banner sambutan ===== --}}
<div class="hero-card mb-4">
    <p class="hero-hello">Selamat datang kembali</p>
    <h1 class="h2">{{ auth()->user()->name }}</h1>
    <p class="hero-text">
        Kelola isi website {{ config('company.name') }} dari sini. ganti logo dan foto banner,
        atur bidang usaha beserta produknya, dan kelola foto galeri.
    </p>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.services.create') }}" class="btn btn-danger"><i class="bi bi-plus-lg me-1"></i>Tambah bidang usaha</a>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-light-outline"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat website</a>
    </div>
</div>

{{-- ===== Ringkasan ===== --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.services.index') }}" class="panel panel-link stat">
            <div class="stat-top">
                <span class="panel-icon"><i class="bi bi-grid"></i></span>
                <i class="bi bi-arrow-right stat-go"></i>
            </div>
            <div class="stat-value">{{ $serviceCount }}</div>
            <div class="stat-label">Bidang usaha</div>
            <div class="stat-note">{{ $hiddenCount ? "{$activeCount} tampil, {$hiddenCount} disembunyikan" : 'Semua tampil di website' }}</div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.services.index') }}" class="panel panel-link stat">
            <div class="stat-top">
                <span class="panel-icon is-dark"><i class="bi bi-box-seam"></i></span>
                <i class="bi bi-arrow-right stat-go"></i>
            </div>
            <div class="stat-value">{{ $itemCount }}</div>
            <div class="stat-label">Total produk</div>
            <div class="stat-note">Di semua bidang usaha</div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.images.index') }}" class="panel panel-link stat">
            <div class="stat-top">
                <span class="panel-icon"><i class="bi bi-images"></i></span>
                <i class="bi bi-arrow-right stat-go"></i>
            </div>
            <div class="stat-value">{{ $customImageCount }}<small> / {{ $slotCount }}</small></div>
            <div class="stat-label">Gambar situs diganti</div>
            <div class="meter" role="progressbar" aria-valuenow="{{ $imagePct }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $imagePct }}%"></span></div>
            <div class="stat-note mt-2">Sisanya memakai gambar bawaan</div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.gallery.index') }}" class="panel panel-link stat">
            <div class="stat-top">
                <span class="panel-icon is-dark"><i class="bi bi-collection"></i></span>
                <i class="bi bi-arrow-right stat-go"></i>
            </div>
            <div class="stat-value">{{ $galleryCount }}</div>
            <div class="stat-label">Foto galeri</div>
            <div class="stat-note">Di halaman Galeri</div>
        </a>
    </div>
</div>

{{-- ===== Pintasan: teks tiap halaman website (menu lainnya sudah ada di sidebar) ===== --}}
<h2 class="section-title mb-3">Edit Teks Halaman</h2>
<div class="panel panel-flush">
    @foreach ($pageTabs as $key => $label)
        <a href="{{ route('admin.pages.edit', $key) }}" class="quick">
            <span @class(['panel-icon', 'is-dark' => $loop->even])><i class="bi bi-file-earmark-text"></i></span>
            <span><span class="t d-block">{{ $label }}</span><span class="d-block d">Atur tulisan di halaman {{ $label }} website.</span></span>
            <i class="bi bi-chevron-right go"></i>
        </a>
    @endforeach
</div>
@endsection