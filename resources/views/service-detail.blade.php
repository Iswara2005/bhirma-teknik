@extends('layouts.app')
@section('title', $service['name'] . ' | ' . $company['name'])

@section('content')

{{-- ========== ATAS: FOTO + PENJELASAN ========== --}}
<section class="container pt-4 pb-5">
    <a href="{{ route('services') }}" class="link-dark d-inline-block mb-4" aria-label="Kembali ke Bidang Usaha" title="Kembali ke Bidang Usaha">
        <i class="bi bi-arrow-left-circle-fill fs-1"></i>
    </a>

    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            {{-- Klik foto = tampil besar + zoom (public/js/lightbox.js). Tanpa JS, tautan membuka foto besar. --}}
            <a href="{{ $service['image_full'] }}" data-lightbox data-caption="{{ $service['name'] }}"
               class="lightbox-trigger d-block position-relative" aria-label="Perbesar foto {{ $service['name'] }}">
                <div class="ratio ratio-4x3">
                    <img src="{{ $service['image'] }}" alt="{{ $service['name'] }}" class="w-100 h-100 object-fit-cover">
                </div>
                <span class="lightbox-hint"><i class="bi bi-zoom-in me-1"></i>Klik untuk memperbesar</span>
            </a>
        </div>
        <div class="col-lg-6">
            <h1 class="fw-bold display-6 mb-3 text-break">{{ $service['name'] }}</h1>
            <p class="text-secondary fs-5 mb-4 text-break">{{ $service['desc'] }}</p>
            <a href="{{ $service['wa_url'] }}" target="_blank" rel="noopener" class="btn btn-danger btn-lg rounded-0 fw-semibold px-4">
                <i class="bi bi-whatsapp me-2"></i>Tanya via WhatsApp
            </a>
        </div>
    </div>
</section>

{{-- ========== DETAIL PRODUK (TAB) ========== --}}
@if ($service['items'])
<section class="container pb-5">
    <h2 class="h3 fw-bold mb-4">Detail produk</h2>

    {{-- Daftar tab: bisa digeser ke samping di layar kecil --}}
    <ul class="nav nav-underline nav-tabs-bt flex-nowrap overflow-x-auto border-bottom mb-4" role="tablist">
        @foreach ($service['items'] as $item)
            <li class="nav-item" role="presentation">
                <button @class(['nav-link', 'text-nowrap', 'active' => $loop->first])
                        id="tab-{{ $loop->index }}"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-{{ $loop->index }}"
                        type="button" role="tab"
                        aria-controls="pane-{{ $loop->index }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $item['name'] }}</button>
            </li>
        @endforeach
    </ul>

    {{-- Isi tab --}}
    <div class="tab-content">
        @foreach ($service['items'] as $item)
            <div @class(['tab-pane', 'fade', 'show active' => $loop->first])
                 id="pane-{{ $loop->index }}"
                 role="tabpanel"
                 aria-labelledby="tab-{{ $loop->index }}"
                 tabindex="0">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-6">
                        <h3 class="h4 fw-bold mb-3 text-break">{{ $item['name'] }}</h3>
                        <p class="text-secondary fs-5 mb-4 text-break">{{ $item['desc'] }}</p>
                        <a href="{{ $item['wa_url'] }}" target="_blank" rel="noopener" class="btn btn-outline-dark rounded-0 fw-semibold">
                            <i class="bi bi-whatsapp me-2"></i>Tanyakan produk ini
                        </a>
                    </div>

                    <div class="col-lg-6">
                        <ul class="list-unstyled mb-0 border-top">
                            @foreach ($item['points'] as $point)
                                <li class="d-flex gap-3 py-3 border-bottom">
                                    <i class="bi bi-check2 text-danger fs-5"></i>
                                    <span class="text-break">{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

@endsection

@push('scripts')
<script src="{{ asset('js/lightbox.js') }}" defer></script>
@endpush