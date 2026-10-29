@extends('layouts.app')
@section('title', 'Tentang Kami | ' . $company['name'])

@section('content')

@php
    // Nama perusahaan di dalam paragraf profil otomatis dicetak tebal (teks tetap di-escape, jadi aman).
    $bold = fn (string $text) => str_replace(e($company['name']), '<strong>'.e($company['name']).'</strong>', e($text));
    $misiList = collect([1, 2, 3])->map(fn ($n) => $pageText["misi_$n"])->filter(fn ($m) => filled($m));
@endphp

<x-page-header
    :image="$images['about_header']"
    :eyebrow="$pageText['eyebrow']"
    :title="$pageText['title']"
    :subtitle="$pageText['subtitle']"
    :lead="$pageText['lead']" />

{{-- ========== PROFIL ========== --}}
<section class="container py-5">
    <div class="row align-items-center g-5 py-4">
        <div class="col-md-6">
            <p class="eyebrow fs-5 fw-bold text-accent text-uppercase mb-2">{{ $pageText['profil_eyebrow'] }}</p>
            <h2 class="fw-bold display-6">{{ $pageText['profil_title'] }}</h2>
            <div class="text-soft text-justify mt-3 d-flex flex-column gap-3">
                @foreach ([1, 2, 3] as $n)
                    <p class="mb-0">{!! $bold($pageText["profil_$n"]) !!}</p>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <div class="rounded-4 overflow-hidden shadow-lg border border-secondary border-opacity-25">
                <img src="{{ $images['about'] }}" class="w-100 h-100 object-fit-cover about-photo" alt="Profil {{ $company['name'] }}">
            </div>
        </div>
    </div>
</section>

{{-- ========== VISI & MISI ========== --}}
<section class="py-5">
    <div class="container py-3">
        <div class="text-center mb-4">
            <p class="eyebrow mb-1">{{ $pageText['visi_eyebrow'] }}</p>
            <h2 class="fw-bold">{{ $pageText['visi_title'] }}</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="cat-card p-4">
                    <h3 class="h5 fw-bold"><i class="bi bi-eye text-accent-dark me-2"></i>Visi</h3>
                    <p class="text-soft small mb-0">{{ $pageText['visi'] }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cat-card p-4">
                    <h3 class="h5 fw-bold"><i class="bi bi-bullseye text-accent-dark me-2"></i>Misi</h3>
                    <ul class="text-soft small mb-0 ps-3">
                        @foreach ($misiList as $misi)
                            <li @class(['mb-2' => ! $loop->last])>{{ $misi }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== TUJUAN PERUSAHAAN ========== --}}
<section class="position-relative overflow-hidden bg-black text-white py-5">
    <x-bg-image :src="$images['tujuan']" alt="Tujuan Perusahaan" />

    <div class="container py-4 position-relative z-2">
        <div class="text-center mb-5">
            <p class="fs-6 fw-bold text-uppercase text-danger mb-2">{{ $pageText['goals_eyebrow'] }}</p>
            <h2 class="display-6 fw-bold mb-3">{{ $pageText['goals_title'] }}</h2>
            <p class="text-white text-opacity-75 col-lg-6 mx-auto mb-0">{{ $pageText['goals_lead'] }}</p>
        </div>

        <div class="row g-4">
            @foreach ([1, 2, 3] as $n)
                <div class="col-md-4">
                    <div class="h-100 border-top border-danger border-4 pt-4">
                        <span class="display-4 fw-bold text-danger lh-1">{{ sprintf('%02d', $n) }}</span>
                        <h3 class="h4 fw-bold mt-3 mb-2">{{ $pageText["goal_{$n}_title"] }}</h3>
                        <p class="text-white text-opacity-75 mb-0">{{ $pageText["goal_{$n}_desc"] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== KEUNGGULAN PERUSAHAAN (BENTO GRID) ========== --}}
<section class="bg-light py-5">
    <div class="container py-4">
        <div class="mb-5">
            <p class="fs-6 fw-bold text-uppercase text-danger mb-2">{{ $pageText['adv_eyebrow'] }}</p>
            <h2 class="display-6 fw-bold mb-0">{{ $pageText['adv_title'] }}</h2>
        </div>

        <div class="row g-3">
            {{-- Kotak besar merah: bidang usaha (tiap badge jadi link) --}}
            <div class="col-lg-7">
                <div class="bg-danger text-white h-100 p-4 p-lg-5">
                    <p class="display-1 fw-bold lh-1 mb-2">{{ count($services) }}</p>
                    <h3 class="h3 fw-bold mb-3">{{ $pageText['adv_services_title'] }}</h3>
                    <p class="text-white text-opacity-75 mb-4">{{ $pageText['adv_services_lead'] }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($services as $s)
                            <a href="{{ route('services.show', $s['slug']) }}"
                               class="badge rounded-pill bg-black bg-opacity-25 text-white text-decoration-none fw-normal px-3 py-2 link-hover-white">{{ $s['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Kotak foto: melayani semua kalangan --}}
            <div class="col-lg-5">
                <div class="position-relative overflow-hidden text-white h-100 d-flex align-items-end">
                    <x-bg-image :src="$images['layanan']" :overlay="50" />
                    <div class="position-relative z-2 p-4 p-lg-5 pt-5">
                        <i class="bi bi-people-fill fs-2 text-white"></i>
                        <h3 class="h4 fw-bold mt-2 mb-2">{{ $pageText['adv_people_title'] }}</h3>
                        <p class="text-white text-opacity-75 mb-0">{{ $pageText['adv_people_lead'] }}</p>
                    </div>
                </div>
            </div>

            @foreach ([1, 2, 3] as $n)
                <div class="col-md-6 col-lg-4">
                    <div class="bg-white h-100 p-4 p-lg-5 border-top border-danger border-4 shadow-sm card-hover">
                        <i class="bi {{ $keuntungan[$n - 1]['icon'] ?? 'bi-check-circle-fill' }} fs-2 text-danger"></i>
                        <h3 class="h5 fw-bold mt-3 mb-2">{{ $pageText["adv_{$n}_title"] }}</h3>
                        <p class="text-secondary mb-0">{{ $pageText["adv_{$n}_desc"] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== CTA WHATSAPP + PETA ========== --}}
<section class="bg-danger text-white">
    <div class="container py-5">
        <div class="row align-items-center g-4 g-lg-5 py-lg-4">
            <div class="col-lg-7">
                <p class="fs-6 fw-bold text-uppercase text-white text-opacity-75 mb-2">{{ $pageText['cta_eyebrow'] }}</p>
                <h2 class="display-6 fw-bold mb-3">{{ $pageText['cta_title'] }}</h2>
                <p class="fs-5 text-white text-opacity-75 mb-4">{{ $pageText['cta_lead'] }}</p>
                <a href="{{ $company['wa_url'] }}" target="_blank" rel="noopener" class="btn btn-dark btn-lg rounded-0 px-4 fw-semibold">
                    <i class="bi bi-whatsapp me-2"></i>{{ $pageText['cta_button'] }}
                </a>
            </div>

            <div class="col-lg-5">
                <div class="bg-white shadow-lg overflow-hidden">
                    <x-map-embed :company="$company" />
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 p-4 text-dark">
                        <div class="d-flex gap-3">
                            <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
                            <div>
                                <h3 class="h6 fw-bold mb-1">{{ $company['name'] }}</h3>
                                <p class="small text-secondary mb-0">{{ $company['address'] }}</p>
                            </div>
                        </div>
                        <a href="{{ $company['maps_url'] }}" target="_blank" rel="noopener" class="btn btn-danger rounded-0 fw-semibold px-4 text-nowrap">Buka Peta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection