@extends('layouts.app')
@section('title', 'Galeri | ' . $company['name'])

@push('styles')
    <link href="{{ asset('css/gallery.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- ========== HERO (foto latar dari Admin > Gambar Situs > gallery_header) ========== --}}
<x-page-header
    :image="$images['gallery_header']"
    :eyebrow="$text['eyebrow']"
    :title="$text['title']"
    :subtitle="$text['subtitle']"
    :lead="$text['lead']" />

{{-- ========== FILTER KATEGORI ==========
     Tersembunyi sampai public/js/gallery.js aktif, supaya tanpa JS tidak ada tombol mati.
     Menempel di bawah navbar saat halaman di-scroll. --}}
@if ($photos->isNotEmpty() && $categories->count() > 1)
    <div class="gallery-filter" hidden>
        <div class="gallery-filter-inner" role="group" aria-label="Filter kategori foto">
            <button type="button" class="gallery-chip is-active" data-filter="*" aria-pressed="true">
                Semua <span>{{ $photos->count() }}</span>
            </button>
            @foreach ($categories as $category)
                <button type="button" class="gallery-chip" data-filter="{{ $category }}" aria-pressed="false">
                    {{ $category }} <span>{{ $photos->where('category', $category)->count() }}</span>
                </button>
            @endforeach
        </div>
    </div>
@endif

{{-- ========== GRID FOTO ========== --}}
<section class="gallery-section">
    <div class="container">
        @if ($photos->isEmpty())
            <div class="text-center text-secondary py-5">
                <i class="bi bi-images display-4 d-block mb-3"></i>
                <p class="mb-0">Foto galeri akan segera hadir.</p>
            </div>
        @else
            {{-- Klik foto = lightbox dengan zoom dan panah (public/js/lightbox.js) --}}
            <div class="gallery-grid" id="galleryGrid">
                @foreach ($photos as $photo)
                    @php $caption = $photo['title'] ?: ($photo['category'] ?: $company['name']); @endphp
                    <a href="{{ $photo['image_full'] }}"
                       data-lightbox data-lightbox-group="galeri"
                       data-caption="{{ $caption }}"
                       data-category="{{ $photo['category'] }}"
                       aria-label="Perbesar foto: {{ $caption }}"
                       class="gallery-tile">
                        <img src="{{ $photo['image'] }}" alt="{{ $caption }}" loading="lazy" decoding="async">
                        @if ($photo['title'] || $photo['category'])
                            <span class="gallery-cap">
                                @if ($photo['category'])<small>{{ $photo['category'] }}</small>@endif
                                @if ($photo['title'])<strong>{{ $photo['title'] }}</strong>@endif
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/lightbox.js') }}" defer></script>
    <script src="{{ asset('js/gallery.js') }}" defer></script>
@endpush