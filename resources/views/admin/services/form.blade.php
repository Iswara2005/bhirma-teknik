@php
    $isNew = ! $service->exists;
    $action = $isNew ? route('admin.services.store') : route('admin.services.update', $service);

    // Isi daftar produk: input lama (bila validasi gagal) atau data dari database
    $rows = old('items', $isNew ? [] : $service->items->map(fn ($item) => [
        'name' => $item->name, 'desc' => $item->desc, 'points' => $item->points,
    ])->all());
@endphp

@extends('layouts.admin')
@section('title', $isNew ? 'Tambah Bidang Usaha' : 'Edit Bidang Usaha')

@section('content')
<a href="{{ route('admin.services.index') }}" class="back-link"><i class="bi bi-arrow-left"></i>Kembali ke daftar</a>
<div class="page-head">
    <h1 class="page-title">{{ $isNew ? 'Tambah Bidang Usaha' : 'Edit: '.$service->name }}</h1>
</div>

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate
      class="panel panel-body">
    @csrf
    @unless ($isNew) @method('PUT') @endunless

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="mb-3">
                <label for="name" class="form-label">Nama usaha <span class="text-danger">*</span></label>
                <input id="name" name="name" type="text" maxlength="100" value="{{ old('name', $service->name) }}"
                       @class(['form-control', 'is-invalid' => $errors->has('name')])>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Contoh: Komputer.</div>
            </div>

            <div class="mb-3">
                <label for="desc" class="form-label">Deskripsi singkat <span class="text-danger">*</span></label>
                <textarea id="desc" name="desc" rows="4" maxlength="300"
                          @class(['form-control', 'is-invalid' => $errors->has('desc')])>{{ old('desc', $service->desc) }}</textarea>
                @error('desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Maksimal 300 karakter.</div>
            </div>

            <div class="row g-3">
                <div class="col-sm-5">
                    <label for="sort_order" class="form-label">Urutan</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                           value="{{ old('sort_order', $service->sort_order) }}" placeholder="Otomatis di paling akhir"
                           @class(['form-control', 'is-invalid' => $errors->has('sort_order')])>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Urutan tampil di website.</div>
                </div>
                <div class="col-sm-7 d-flex align-items-center">
                    <div class="form-check form-switch mt-sm-3">
                        {{-- Nilai 0 dikirim bila saklar mati (checkbox yang tidak dicentang tidak dikirim browser) --}}
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                               @checked(old('is_active', $service->is_active))>
                        <label class="form-check-label fw-semibold" for="is_active">Tampilkan di website</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <label for="image" class="form-label">
                Foto @if ($isNew) <span class="text-danger">*</span> @endif
            </label>
            {{-- Pratinjau = bentuk kotak di website (4:3). Foto apa pun dipotong di tengah, persis seperti ini. --}}
            <div class="ratio ratio-4x3 preview-box mb-2">
                <div class="d-flex flex-column align-items-center justify-content-center text-secondary" id="preview-empty" @if ($service->image) hidden @endif>
                    <i class="bi bi-image fs-1"></i>
                    <span class="small">Pratinjau foto</span>
                </div>
                <img id="preview" src="{{ $service->image ? App\Support\ImageStore::url($service->image) : '' }}" alt="" class="object-fit-cover position-absolute top-0 start-0 w-100 h-100" @unless ($service->image) hidden @endunless>
            </div>
            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-preview="#preview"
                   @class(['form-control', 'is-invalid' => $errors->has('image')])>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">
                JPG, PNG, atau WebP, maksimal 4 MB.
                @unless ($isNew) Kosongkan jika tidak ingin mengganti foto. @endunless
            </div>
        </div>
    </div>

    {{-- ========== DETAIL PRODUK ========== --}}
    <div class="mt-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
            <div>
                <h2 class="section-title mb-1">Detail produk</h2>
                <p class="small text-secondary mb-0">
                    Tampil sebagai tab "Detail produk" di halaman bidang usaha. kosongkan
                    bila tidak ada produk, bagian itu tidak ditampilkan di website.
                </p>
            </div>
            <button type="button" id="add-item" class="btn btn-outline-dark btn-sm fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>Tambah produk
            </button>
        </div>

        {{-- Penanda: form ini memang mengirim daftar produk (lihat ServiceController::syncItems) --}}
        <input type="hidden" name="items_present" value="1">

        @error('items') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

        <div id="items">
            @foreach ($rows as $key => $row)
                @include('admin.services.item', ['key' => $key, 'item' => $row])
            @endforeach
        </div>
        <p id="items-empty" class="text-secondary small fst-italic mb-0" @if (count($rows)) hidden @endif>
            Belum ada produk. Klik "Tambah produk" untuk mengisi.
        </p>
    </div>

    <template id="item-template">@include('admin.services.item', ['key' => '__KEY__', 'item' => []])</template>

    {{-- Bilah simpan menempel di dasar layar supaya tombol selalu terjangkau --}}
    <div class="action-bar">
        <button class="btn btn-danger px-4"><i class="bi bi-check-lg me-1"></i>Simpan</button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-dark">Batal</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // ===== Detail produk: tambah / hapus baris =====
    (() => {
        const list = document.getElementById('items');
        const empty = document.getElementById('items-empty');
        const template = document.getElementById('item-template');
        let next = 100000; // nomor unik baris baru; urutan di server mengikuti urutan di layar

        const refresh = () => {
            list.querySelectorAll('[data-item]').forEach((row, i) => {
                row.querySelector('[data-item-title]').textContent = 'Produk ' + (i + 1);
            });
            empty.hidden = list.children.length > 0;
        };

        document.getElementById('add-item').addEventListener('click', () => {
            list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__KEY__', next++));
            refresh();
            list.lastElementChild.querySelector('input').focus();
        });

        list.addEventListener('click', e => {
            const btn = e.target.closest('[data-remove-item]');
            if (!btn) return;
            btn.closest('[data-item]').remove();
            refresh();
        });

        refresh();
    })();

    // Tampilkan pratinjau saat foto dipilih (menggantikan kotak kosong di form "tambah")
    document.getElementById('image').addEventListener('change', () => {
        document.getElementById('preview').hidden = false;
        document.getElementById('preview-empty').hidden = true;
    });
</script>
@endpush