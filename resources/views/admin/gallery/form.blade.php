@php
    $isNew = ! $photo->exists;
    $action = $isNew ? route('admin.gallery.store') : route('admin.gallery.update', $photo);

    // Pilihan kategori dari config; kategori lama milik foto ini tetap ikut tampil agar tidak hilang
    $categories = collect(config('gallery.categories'));
    if ($photo->category && ! $categories->contains($photo->category)) {
        $categories->push($photo->category);
    }
    $selected = old('category', $photo->category);

    $currentImage = $isNew ? '' : $photo->toCard()['image'];
    $isLink = ! $isNew && \Illuminate\Support\Str::startsWith($photo->image, ['http://', 'https://']);
@endphp

@extends('layouts.admin')
@section('title', $isNew ? 'Tambah Foto Galeri' : 'Edit Foto Galeri')

@section('content')
<a href="{{ route('admin.gallery.index') }}" class="back-link"><i class="bi bi-arrow-left"></i>Kembali ke daftar</a>
<div class="page-head">
    <h1 class="page-title">{{ $isNew ? 'Tambah Foto Galeri' : 'Edit Foto Galeri' }}</h1>
</div>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate class="panel panel-body">
    @csrf
    @unless ($isNew) @method('PUT') @endunless

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="mb-3">
                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select id="category" name="category" @class(['form-select', 'is-invalid' => $errors->has('category')])>
                    <option value="" disabled @selected(! $selected)>Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($selected === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Kategori menjadi tombol filter di halaman Galeri.</div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Judul foto</label>
                <input id="title" name="title" type="text" maxlength="100" value="{{ old('title', $photo->title) }}"
                       placeholder="Contoh: Tampak depan toko"
                       @class(['form-control', 'is-invalid' => $errors->has('title')])>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Tampil sebagai keterangan di pojok foto. Boleh dikosongkan.</div>
            </div>

            <div class="row g-3">
                <div class="col-sm-5">
                    <label for="sort_order" class="form-label">Urutan</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                           value="{{ old('sort_order', $photo->sort_order) }}" placeholder="Otomatis di paling akhir"
                           @class(['form-control', 'is-invalid' => $errors->has('sort_order')])>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Angka kecil tampil lebih dulu.</div>
                </div>
                <div class="col-sm-7 d-flex align-items-center">
                    <div class="form-check form-switch mt-sm-3">
                        {{-- Nilai 0 dikirim bila saklar mati (checkbox yang tidak dicentang tidak dikirim browser) --}}
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                               @checked(old('is_active', $photo->is_active))>
                        <label class="form-check-label fw-semibold" for="is_active">Tampilkan di website</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <label for="image" class="form-label">
                Foto @if ($isNew) <span class="text-danger">*</span> @endif
            </label>
            <div class="ratio ratio-4x3 preview-box mb-2">
                <div class="d-flex flex-column align-items-center justify-content-center text-secondary" id="preview-empty" @if ($currentImage) hidden @endif>
                    <i class="bi bi-image fs-1"></i>
                    <span class="small">Pratinjau foto</span>
                </div>
                <img id="preview" src="{{ $currentImage }}" alt="" class="object-fit-cover position-absolute top-0 start-0 w-100 h-100" @unless ($currentImage) hidden @endunless>
            </div>
            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-preview="#preview"
                   @class(['form-control', 'is-invalid' => $errors->has('image')])>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">
                JPG, PNG, atau WebP, maksimal 4 MB. Foto mendatar (landscape) berlebar minimal 1200 px paling cocok.
                Di galeri, foto dipotong di tengah mengikuti bentuk kotaknya.
                @unless ($isNew) Kosongkan jika tidak ingin mengganti foto. @endunless
            </div>
            @if ($isLink)
                <div class="alert alert-secondary small py-2 mt-2 mb-0">
                    Foto ini masih berupa foto contoh dari internet. Pilih file di atas untuk menggantinya dengan foto Anda.
                </div>
            @endif
        </div>
    </div>

    {{-- Bilah simpan menempel di dasar layar supaya tombol selalu terjangkau --}}
    <div class="action-bar">
        <button class="btn btn-danger px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-dark">Batal</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Saat file dipilih: tampilkan pratinjau (gambarnya diisi oleh skrip di layout admin)
    document.getElementById('image').addEventListener('change', e => {
        if (!e.target.files[0]) return;
        document.getElementById('preview').hidden = false;
        document.getElementById('preview-empty').hidden = true;
    });
</script>
@endpush