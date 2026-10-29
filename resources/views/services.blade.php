@extends('layouts.app')
@section('title', 'Bidang Usaha | ' . $company['name'])

@section('content')

<x-page-header
    :image="$images['service_header']"
    :eyebrow="$pageText['eyebrow']"
    :title="$pageText['title']"
    :subtitle="count($services) . ' ' . $pageText['subtitle_suffix']"
    :lead="$pageText['lead']" />

<section class="container py-5">
    <div class="row g-4 py-3">
        @foreach ($services as $s)
            <div class="col-md-6 col-lg-4">
                <article class="cat-card h-100 overflow-hidden d-flex flex-column">
                    <div class="thumb">
                        <img src="{{ $s['image'] }}" alt="{{ $s['name'] }}" loading="lazy" class="w-100 h-100 object-fit-cover d-block">
                        <span class="badge-num">{{ sprintf('%02d', $loop->iteration) }}</span>
                    </div>

                    <div class="p-4 d-flex flex-column flex-fill">
                        <h2 class="h5 fw-bold mb-2 text-break">{{ $s['name'] }}</h2>
                        <p class="text-secondary mb-4">{{ $pageText['card_text'] }}</p>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('services.show', $s['slug']) }}" class="btn btn-outline-dark rounded-0 fw-semibold btn-sm flex-fill">
                                <i class="bi bi-eye me-1"></i>{{ $pageText['button_detail'] }}
                            </a>
                            <a href="{{ $s['wa_url'] }}" target="_blank" rel="noopener" class="btn btn-danger rounded-0 fw-semibold btn-sm flex-fill">
                                <i class="bi bi-whatsapp me-1"></i>{{ $pageText['button_wa'] }}
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
</section>

@endsection