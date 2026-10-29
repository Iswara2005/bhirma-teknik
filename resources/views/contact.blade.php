@extends('layouts.app')
@section('title', 'Kontak | ' . $company['name'])

@section('content')

@php
    $cards = [
        ['icon' => 'bi-whatsapp',       'label' => 'WhatsApp', 'value' => $company['phone'],         'cta' => $pageText['card_wa_cta'],    'href' => $company['wa_url'],             'blank' => true],
        ['icon' => 'bi-telephone-fill', 'label' => 'Telepon',  'value' => $company['phone'],         'cta' => $pageText['card_phone_cta'], 'href' => 'tel:' . $company['phone'],     'blank' => false],
        ['icon' => 'bi-envelope-fill',  'label' => 'Email',    'value' => $company['email'],         'cta' => $pageText['card_email_cta'], 'href' => 'mailto:' . $company['email'],  'blank' => false],
        ['icon' => 'bi-geo-alt-fill',   'label' => 'Alamat',   'value' => $company['short_address'], 'cta' => $pageText['card_map_cta'],   'href' => $company['maps_url'],           'blank' => true],
    ];

    $steps = [
        [$pageText['step_1_title'], $pageText['step_1_desc']],
        [$pageText['step_2_title'], $pageText['step_2_desc']],
        [$pageText['step_3_title'], $pageText['step_3_desc']],
    ];

    $faqs = [
        ['q' => $pageText['faq_1_q'], 'a' => $pageText['faq_1_a']],
        ['q' => $pageText['faq_2_q'], 'a' => $pageText['faq_2_a']],
        ['q' => $pageText['faq_3_q'], 'a' => $pageText['faq_3_a'], 'link' => [route('services'), $pageText['faq_3_link']]],
        ['q' => $pageText['faq_4_q'], 'a' => $pageText['faq_4_a']],
    ];
@endphp

<x-page-header
    :image="$images['contact_header']"
    :eyebrow="$pageText['eyebrow']"
    :title="$pageText['title']"
    :subtitle="$pageText['subtitle']"
    :lead="$pageText['lead']" />

{{-- ========== KARTU KONTAK CEPAT (menimpa sedikit header) ========== --}}
<section class="container position-relative contact-cards pb-5">
    <div class="row g-4">
        @foreach ($cards as $c)
            <div class="col-sm-6 col-lg-3">
                <a href="{{ $c['href'] }}" @if ($c['blank']) target="_blank" rel="noopener" @endif
                   class="d-block h-100 bg-white p-4 border-top border-danger border-4 shadow-sm card-hover text-decoration-none text-dark">
                    <i class="bi {{ $c['icon'] }} fs-2 text-danger"></i>
                    <p class="eyebrow mt-3 mb-1">{{ $c['label'] }}</p>
                    <p class="fw-semibold mb-2 text-break">{{ $c['value'] }}</p>
                    <span class="small fw-bold text-accent-dark">{{ $c['cta'] }} <i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
        @endforeach
    </div>
</section>

{{-- ========== FORM + LANGKAH ========== --}}
<section class="bg-surface py-5">
    <div class="container py-3">
        <div class="row g-4 g-lg-5">

            {{-- Form: dikirim lewat public/js/contact-form.js --}}
            <div class="col-lg-7">
                <form id="waForm" novalidate
                      data-wa="{{ $company['wa'] }}" data-company="{{ $company['name'] }}"
                      class="bg-white h-100 p-4 p-lg-5 border-top border-danger border-4 shadow-sm">
                    <p class="eyebrow mb-1">{{ $pageText['form_eyebrow'] }}</p>
                    <h2 class="h3 fw-bold mb-2">{{ $pageText['form_title'] }}</h2>
                    <p class="text-secondary mb-4">{{ $pageText['form_lead'] }}</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold small" for="f-name">Nama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text rounded-0 bg-white"><i class="bi bi-person"></i></span>
                            <input id="f-name" name="nama" type="text" class="form-control rounded-0" placeholder="Nama Anda" required>
                            <div class="invalid-feedback">Mohon isi nama Anda.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small" for="f-topic">Bidang yang ditanyakan</label>
                        <select id="f-topic" name="topik" class="form-select rounded-0">
                            <option value="">Pilih (opsional)</option>
                            @foreach ($services as $s)
                                <option>{{ $s['name'] }}</option>
                            @endforeach
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small" for="f-msg">Pesan <span class="text-danger">*</span></label>
                        <textarea id="f-msg" name="pesan" rows="5" class="form-control rounded-0" required
                                  placeholder="Sebutkan produk yang dicari, jumlah, dan kebutuhan Anda"></textarea>
                        <div class="invalid-feedback">Mohon isi pesan Anda.</div>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg rounded-0 w-100 fw-semibold">
                        <i class="bi bi-whatsapp me-2"></i>{{ $pageText['form_button'] }}
                    </button>
                    <p class="small text-secondary text-center mt-3 mb-0">{{ $pageText['form_note'] }}</p>
                </form>
            </div>

            {{-- Langkah --}}
            <div class="col-lg-5">
                <div class="bg-ink text-white h-100 p-4 p-lg-5 border-top border-danger border-4">
                    <p class="eyebrow text-accent mb-1">{{ $pageText['steps_eyebrow'] }}</p>
                    <h2 class="h3 fw-bold mb-4">{{ $pageText['steps_title'] }}</h2>

                    @foreach ($steps as [$title, $text])
                        <div class="d-flex gap-3 mb-4">
                            <span class="fs-3 fw-bold text-accent lh-1">{{ sprintf('%02d', $loop->iteration) }}</span>
                            <div>
                                <h3 class="h6 fw-bold mb-1">{{ $title }}</h3>
                                <p class="small text-white text-opacity-75 mb-0">{{ $text }}</p>
                            </div>
                        </div>
                    @endforeach

                    <hr class="border-secondary opacity-50">
                    <p class="small text-white text-opacity-75 mb-0">
                        <i class="bi bi-check2-circle text-accent me-1"></i>
                        {{ $pageText['steps_note'] }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ========== PETA ========== --}}
<section class="container py-5">
    <div class="row g-4 g-lg-5 align-items-center py-3">
        <div class="col-lg-4">
            <p class="eyebrow mb-1">{{ $pageText['map_eyebrow'] }}</p>
            <h2 class="h3 fw-bold mb-3">{{ $pageText['map_title'] }}</h2>
            <p class="text-secondary">{{ $company['address'] }}</p>
            <a href="{{ $company['maps_url'] }}" target="_blank" rel="noopener" class="btn btn-danger rounded-0 fw-semibold px-4">
                <i class="bi bi-geo-alt-fill me-2"></i>{{ $pageText['map_button'] }}
            </a>
        </div>
        <div class="col-lg-8">
            <x-map-embed :company="$company" ratio="16x9" class="shadow-lg border-bottom border-danger border-4" />
        </div>
    </div>
</section>

{{-- ========== FAQ ========== --}}
<section class="bg-surface py-5">
    <div class="container py-3">
        <div class="text-center mb-4">
            <p class="eyebrow mb-1">{{ $pageText['faq_eyebrow'] }}</p>
            <h2 class="h3 fw-bold mb-0">{{ $pageText['faq_title'] }}</h2>
        </div>

        <div class="col-lg-8 mx-auto">
            <div class="accordion accordion-flush bg-white shadow-sm border-top border-danger border-4" id="faq">
                @foreach ($faqs as $faq)
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq{{ $loop->iteration }}">{{ $faq['q'] }}</button>
                        </h3>
                        <div id="faq{{ $loop->iteration }}" class="accordion-collapse collapse" data-bs-parent="#faq">
                            <div class="accordion-body text-secondary">
                                {{ $faq['a'] }}
                                @isset($faq['link'])
                                    <a href="{{ $faq['link'][0] }}" class="fw-semibold text-accent-dark">{{ $faq['link'][1] }}</a>.
                                @endisset
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/contact-form.js') }}"></script>
@endpush