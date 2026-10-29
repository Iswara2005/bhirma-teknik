@extends('layouts.admin')
@section('title', 'Gambar Situs')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Gambar Situs</h1>
        <p class="page-sub">
            Pilih foto baru lalu klik <strong>Simpan gambar</strong>; gambar di website langsung berganti.
            Format JPG, PNG, atau WebP, maksimal 4 MB.
        </p>
    </div>
</div>

<div class="row g-4">
    @foreach ($slots as $slot)
        @php $slotError = $errors->getBag('slot_'.$slot['key'])->first('image'); @endphp
        <div class="col-md-6 col-xl-4">
            <div class="panel panel-flush h-100 d-flex flex-column">
                {{-- Pratinjau memakai bentuk kotak slot, jadi terlihat seperti hasil akhirnya --}}
                @php $ratio = $slot['ratio'] ?? null; @endphp
                <div class="slot-media">
                    <div class="ratio" style="--bs-aspect-ratio: {{ $ratio ? round(100 / $ratio, 2) : 56.25 }}%;">
                        <img id="preview-{{ $slot['key'] }}" src="{{ $slot['url'] }}" alt="{{ $slot['label'] }}" class="{{ $ratio ? 'object-fit-cover' : 'object-fit-contain' }}" loading="lazy">
                    </div>
                    @if ($slot['is_custom'])
                        <span class="pill pill-on">Diganti</span>
                    @else
                        <span class="pill pill-off">Bawaan</span>
                    @endif
                </div>

                <div class="slot-body">
                    <h2 class="slot-title">{{ $slot['label'] }}</h2>
                    <p class="slot-where"><i class="bi bi-geo-alt me-1"></i>{{ $slot['used_in'] }}</p>

                    <form method="POST" action="{{ route('admin.images.update', $slot['key']) }}" enctype="multipart/form-data" class="mt-auto">
                        @csrf @method('PUT')
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                               data-preview="#preview-{{ $slot['key'] }}"
                               @class(['form-control', 'form-control-sm', 'mb-2', 'is-invalid' => $slotError])>
                        @if ($slotError)
                            <div class="invalid-feedback d-block mb-2">{{ $slotError }}</div>
                        @endif
                        <button class="btn btn-dark btn-sm w-100"><i class="bi bi-upload me-1"></i>Simpan gambar</button>
                    </form>

                    @if ($slot['is_custom'])
                        <form method="POST" action="{{ route('admin.images.destroy', $slot['key']) }}" class="mt-3 text-center"
                              onsubmit="return confirm('Kembali ke gambar bawaan? Gambar yang Anda unggah akan dihapus.')">
                            @csrf @method('DELETE')
                            <button class="link-reset">Kembali ke gambar bawaan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection