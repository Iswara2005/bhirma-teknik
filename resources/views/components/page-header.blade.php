{{-- Banner judul halaman: foto latar + judul besar (putih) + baris kedua (abu) + keterangan.
     Dipakai di Tentang Kami, Bidang Usaha, Kontak, dan Galeri. Gayanya ada di public/css/site.css --}}
@props(['image', 'title', 'subtitle' => null, 'lead' => null, 'eyebrow' => null, 'overlay' => 50])

<section class="page-hero position-relative overflow-hidden">
    <x-bg-image :src="$image" :overlay="$overlay" />
    <div class="position-relative z-2">
        @if ($eyebrow)
            <p class="eyebrow mb-0">{{ $eyebrow }}</p>
        @endif
        <h1 class="page-hero-title">
            {{ $title }}
            @if ($subtitle)<span>{{ $subtitle }}</span>@endif
        </h1>
        @if ($lead)
            <p class="page-hero-lead">{{ $lead }}</p>
        @endif
    </div>
</section>