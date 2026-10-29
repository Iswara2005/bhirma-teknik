@extends('layouts.admin')
@section('title', 'Galeri')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Galeri</h1>
        <p class="page-sub">
            Atur foto-foto di halaman Galeri. Tulisan banner diubah lewat
            <a href="{{ route('admin.pages.edit', 'gallery') }}" class="text-accent-dark fw-semibold">Teks Halaman &rsaquo; Galeri</a>,
            dan foto latar banner lewat
            <a href="{{ route('admin.images.index') }}" class="text-accent-dark fw-semibold">Gambar Situs</a>.
        </p>
    </div>
</div>

{{-- ========== DAFTAR FOTO ========== --}}
<div class="mb-3 d-flex flex-wrap justify-content-between align-items-end gap-2">
    <div>
        <h2 class="section-title mb-1">Foto galeri</h2>
        <p class="small text-secondary mb-0">
            {{ $photos->count() }} foto. Foto dengan urutan terkecil tampil paling depan, dan foto pertama tampil paling besar.
        </p>
    </div>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-danger">
        <i class="bi bi-plus-lg me-1"></i>Tambah foto
    </a>
</div>

{{--
    Di HP kolom Kategori, Urutan, dan Status disembunyikan supaya tombol Edit/Hapus
    langsung terlihat. Kategori dan status tetap terbaca lewat ringkasan kecil di bawah judul.
--}}
<div class="panel panel-flush">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th class="ps-3 ps-md-4 th-foto">Foto</th>
                    <th>Judul</th>
                    <th class="d-none d-md-table-cell">Kategori</th>
                    <th class="text-center d-none d-md-table-cell">Urutan</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-end pe-3 pe-md-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($photos as $photo)
                    @php
                        [$pillClass, $pillText] = $photo->is_active ? ['pill-on', 'Tampil'] : ['pill-off', 'Disembunyikan'];
                        $label = $photo->title ?: '(tanpa judul)';
                    @endphp
                    <tr>
                        <td class="ps-3 ps-md-4">
                            <div class="ratio ratio-4x3 thumb-sm">
                                <img src="{{ $photo->toCard()['image'] }}" alt="{{ $label }}" class="object-fit-cover" loading="lazy">
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-break">{{ $label }}</div>
                            <div class="d-md-none d-flex flex-wrap align-items-center gap-2 mt-1 small text-secondary">
                                <span>{{ $photo->category }}</span>
                                <span class="pill {{ $pillClass }}">{{ $pillText }}</span>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="pill pill-plain pill-off">{{ $photo->category }}</span>
                        </td>
                        <td class="text-center d-none d-md-table-cell"><span class="order-chip">{{ $photo->sort_order }}</span></td>
                        <td class="d-none d-md-table-cell">
                            <span class="pill {{ $pillClass }}">{{ $pillText }}</span>
                        </td>
                        <td class="text-end pe-3 pe-md-4 text-nowrap">
                            <div class="table-actions">
                                <a href="{{ route('admin.gallery.edit', $photo) }}" class="btn btn-outline-dark btn-sm"
                                   aria-label="Edit {{ $label }}" title="Edit">
                                    <i class="bi bi-pencil"></i><span class="d-none d-md-inline ms-1">Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $photo) }}" class="m-0"
                                      onsubmit="return confirm('Hapus foto &quot;{{ addslashes($label) }}&quot;? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm icon-btn" aria-label="Hapus {{ $label }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-collection"></i>
                                <p class="mb-1">Belum ada foto di galeri.</p>
                                <p class="small text-secondary mb-3">
                                    Saat ini website menampilkan foto contoh bawaan sistem. Tambah foto Anda sendiri
                                    untuk menggantikannya.
                                </p>
                                <a href="{{ route('admin.gallery.create') }}" class="btn btn-danger btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah foto
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection