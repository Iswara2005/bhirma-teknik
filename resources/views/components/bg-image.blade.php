{{-- Foto latar penuh + lapisan gelap. Letakkan konten di atasnya dengan "position-relative z-2". --}}
@props(['src', 'alt' => '', 'overlay' => 75])

<img src="{{ $src }}" alt="{{ $alt }}" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover z-0">
<div class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-{{ $overlay }} z-1"></div>
