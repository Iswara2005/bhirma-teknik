@extends('layouts.admin')
@section('title', 'Kontak & Alamat')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Kontak &amp; Alamat</h1>
        <p class="page-sub">
            Nomor telepon/WhatsApp, email, dan alamat yang tampil di footer, halaman Kontak, dan Tentang Kami.
            Tulisan lain di halaman diatur lewat
            <a href="{{ route('admin.pages.edit') }}" class="text-accent-dark fw-semibold">Teks Halaman</a>.
        </p>
    </div>
</div>

@php
    // [nama, label, jenis, maks, lebar kolom, petunjuk]
    $groups = [
        ['title' => 'Kontak', 'desc' => 'Dipakai di footer dan halaman Kontak.', 'icon' => 'bi-telephone', 'fields' => [
            ['phone', 'Nomor telepon / WhatsApp', 'tel', 20, 'col-md-6',
                'Masukkan nomor telepon atau WhatsApp yang valid.'],
            ['email', 'Email', 'email', 255, 'col-md-6',
                'Tampil di footer dan halaman Kontak.'],
        ]],
        ['title' => 'Alamat & peta', 'desc' => 'Dipakai di footer, halaman Kontak, dan Tentang Kami.', 'icon' => 'bi-geo-alt', 'fields' => [
            ['address', 'Alamat lengkap', 'textarea', 255, 'col-12',
                'Tampil di footer dan bagian "Kunjungi Kami".'],
            ['short_address', 'Alamat singkat', 'text', 100, 'col-md-6',
                'Contoh: Bebalang, Bangli, Bali. Tampil di kartu kontak.'],
            ['maps_query', 'Lokasi di peta', 'text', 200, 'col-md-6',
                'Kata pencarian untuk peta di halaman Kontak. Ketik seperti mencari di Google Maps, misalnya: CV. Bhirma Teknik, Bangli, Bali.'],
            ['maps_url', 'Link Google Maps', 'url', 500, 'col-12',
                'Tujuan tombol "Buka di Google Maps". Buka lokasi di Google Maps, pilih Bagikan, lalu salin link-nya (diawali https://).'],
        ]],
    ];
@endphp

<form method="POST" action="{{ route('admin.company.update') }}" novalidate>
    @csrf @method('PUT')

    @foreach ($groups as $group)
        <div class="panel panel-edit mb-4">
            <div class="panel-head">
                <span class="panel-icon is-dark"><i class="bi {{ $group['icon'] }}"></i></span>
                <div>
                    <h2>{{ $group['title'] }}</h2>
                    <p>{{ $group['desc'] }}</p>
                </div>
            </div>

            <div class="panel-body">
                <div class="row g-3">
                    @foreach ($group['fields'] as [$name, $label, $type, $max, $col, $help])
                        <div class="{{ $col }}">
                            <label for="f-{{ $name }}" class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                            @if ($type === 'textarea')
                                <textarea id="f-{{ $name }}" name="{{ $name }}" rows="3" maxlength="{{ $max }}"
                                          @class(['form-control', 'is-invalid' => $errors->has($name)])>{{ old($name, $company[$name]) }}</textarea>
                            @else
                                <input id="f-{{ $name }}" name="{{ $name }}" type="{{ $type }}" maxlength="{{ $max }}"
                                       value="{{ old($name, $company[$name]) }}"
                                       @class(['form-control', 'is-invalid' => $errors->has($name)])>
                            @endif
                            @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">{{ $help }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <button class="btn btn-danger mb-3"><i class="bi bi-check-lg me-1"></i>Simpan kontak &amp; alamat</button>
    <p class="small text-secondary mb-4">
        Setelah disimpan, buka halaman
        <a href="{{ route('contact') }}" target="_blank" rel="noopener" class="text-accent-dark fw-semibold">Kontak</a>
        dan pastikan pin di peta sudah tepat.
    </p>
</form>
@endsection