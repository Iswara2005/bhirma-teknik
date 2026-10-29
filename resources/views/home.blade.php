@extends('layouts.app')
@section('title', $company['name'] . ' | Perdagangan Eceran di ' . $company['city'])

@section('content')

{{-- ========== HERO ========== --}}
<section class="position-relative overflow-hidden text-white bg-black py-5 border-bottom border-danger border-4">
    <x-bg-image :src="$images['hero']" :alt="$company['name']" />

    <div class="container py-lg-5 position-relative z-2">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9 col-xl-8">
                <span class="badge rounded-0 bg-danger bg-opacity-10 border border-danger border-opacity-50 text-white fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-block p-1 bg-danger rounded-circle"></span>
                    {{ $pageText['hero_badge'] }}
                </span>
                <h1 class="display-4 fw-bold lh-sm mb-4">
                    {{ $pageText['hero_title_1'] }}
                    <span class="text-danger">{{ $pageText['hero_title_accent'] }}</span>
                    {{ $pageText['hero_title_2'] }}
                </h1>

                <p class="lead text-light opacity-75 mt-2">{{ $pageText['hero_lead'] }}</p>

                <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                    <a href="{{ route('about') }}" class="btn btn-danger btn-lg rounded-0 px-4 fw-semibold">{{ $pageText['hero_button'] }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== BIDANG USAHA (slider) ========== --}}
<section class="bg-light py-5">
    <div class="container py-4">
        <div class="col-lg-7 p-0 mb-4">
            <h2 class="fw-bold mb-3">{{ count($services) }} {{ $pageText['services_title'] }}</h2>
            <p class="text-secondary mb-0">{{ $pageText['services_lead'] }}</p>
        </div>

        {{-- Digerakkan oleh public/js/service-slider.js --}}
        <div class="row flex-nowrap overflow-hidden user-select-none g-4" id="bidangScroll">
            @foreach ($services as $s)
                <div class="col-10 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 bg-transparent rounded-0">
                        <div class="position-relative">
                            <div class="ratio ratio-4x3">
                                <img src="{{ $s['image'] }}" class="w-100 h-100 object-fit-cover" alt="{{ $s['name'] }}">
                            </div>
                        </div>
                        <div class="card-body px-0 pt-4">
                            <h3 class="h4 fw-normal mb-2">
                                <a href="{{ route('services.show', $s['slug']) }}"
                                   class="link-dark link-underline-opacity-0 link-underline-opacity-100-hover">{{ $s['name'] }}</a>
                            </h3>
                            <p class="text-secondary mb-0 text-break">{{ $s['desc'] }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('services') }}" class="btn btn-outline-dark btn-lg fw-semibold px-4 rounded-0">{{ $pageText['services_button'] }}</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/service-slider.js') }}"></script>
@endpush