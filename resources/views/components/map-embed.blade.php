{{-- Google Maps lokasi perusahaan. Kelas tambahan dari pemanggil ikut ke pembungkus. --}}
@props(['company', 'ratio' => '4x3'])

<div {{ $attributes->class(['ratio', "ratio-$ratio"]) }}>
    <iframe src="{{ $company['maps_embed'] }}"
            class="border-0"
            allowfullscreen
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi {{ $company['name'] }} di Google Maps"></iframe>
</div>
