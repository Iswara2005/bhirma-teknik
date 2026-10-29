@extends('layouts.admin')
@section('title', 'Bidang Usaha')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Bidang Usaha</h1>
        <p class="page-sub">
            Tambah, ubah, atau hapus bidang usaha yang ditampilkan di halaman depan. Setiap bidang usaha bisa memiliki beberapa produk.
        </p>
    </div>
    <a href="{{ route('admin.services.create') }}" class="btn btn-danger">
        <i class="bi bi-plus-lg me-1"></i>Tambah bidang usaha
    </a>
</div>

{{--
    Di HP (di bawah "md") kolom Urutan, Produk, dan Status disembunyikan supaya tombol
    Edit/Hapus langsung terlihat tanpa menggeser tabel. Status dan jumlah produk
    tetap terbaca lewat ringkasan kecil di bawah nama.
--}}
<div class="panel panel-flush">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th class="ps-3 ps-md-4 th-foto">Foto</th>
                    <th>Nama</th>
                    <th class="text-center d-none d-md-table-cell">Urutan</th>
                    <th class="text-center d-none d-md-table-cell">Produk</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-end pe-3 pe-md-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    @php
                        [$pillClass, $pillText] = $service->is_active ? ['pill-on', 'Tampil'] : ['pill-off', 'Disembunyikan'];
                    @endphp
                    <tr>
                        <td class="ps-3 ps-md-4">
                            <div class="ratio ratio-4x3 thumb-sm">
                                <img src="{{ App\Support\ImageStore::url($service->image) }}" alt="{{ $service->name }}" class="object-fit-cover" loading="lazy">
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-break">{{ $service->name }}</div>
                            <div class="d-md-none d-flex flex-wrap align-items-center gap-2 mt-1 small text-secondary">
                                <span class="pill {{ $pillClass }}">{{ $pillText }}</span>
                                <span>{{ $service->items_count }} produk</span>
                            </div>
                        </td>
                        <td class="text-center d-none d-md-table-cell"><span class="order-chip">{{ $service->sort_order }}</span></td>
                        <td class="text-center d-none d-md-table-cell">{{ $service->items_count }}</td>
                        <td class="d-none d-md-table-cell">
                            <span class="pill {{ $pillClass }}">{{ $pillText }}</span>
                        </td>
                        <td class="text-end pe-3 pe-md-4 text-nowrap">
                            <div class="table-actions">
                                {{-- Di HP tombol Edit hanya ikon; tulisan "Edit" muncul di layar lebar --}}
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-outline-dark btn-sm"
                                   aria-label="Edit {{ $service->name }}" title="Edit">
                                    <i class="bi bi-pencil"></i><span class="d-none d-md-inline ms-1">Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="m-0"
                                      onsubmit="return confirm('Hapus &quot;{{ addslashes($service->name) }}&quot; beserta {{ $service->items_count }} produk di dalamnya? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm icon-btn" aria-label="Hapus {{ $service->name }}" title="Hapus">
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
                                <i class="bi bi-grid"></i>
                                <p class="mb-3">Belum ada bidang usaha.</p>
                                <a href="{{ route('admin.services.create') }}" class="btn btn-danger btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah bidang usaha</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection