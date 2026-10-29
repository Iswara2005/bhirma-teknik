<footer class="footer-bt pt-5">
    <div class="container pb-4">
        <div class="row g-4">
            {{-- Logo & nama --}}
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="logo-badge rounded-0"><img src="{{ $images['logo'] }}" alt="Logo {{ $company['name'] }}"></span>
                    <h5 class="text-white fw-bold mb-0">{{ $company['name'] }}</h5>
                </div>
                <p class="small mt-3 text-secondary">{{ $company['tagline'] }} · {{ $company['city'] }}</p>
            </div>

            {{-- Bidang usaha --}}
            <div class="col-md-6 col-lg-4">
                <h6 class="text-white fw-bold mb-3">Bidang Usaha</h6>
                <ul class="list-unstyled small">
                    @foreach (array_slice($services, 0, 9) as $s)
                        <li class="mb-1 text-secondary">{{ $s['name'] }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="col-md-6 col-lg-4">
                <h6 class="text-white fw-bold mb-3">Kontak</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2 text-secondary">
                        <i class="bi bi-geo-alt text-accent me-2"></i>
                        <a href="{{ $company['maps_url'] }}" target="_blank" rel="noopener">{{ $company['address'] }}</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone text-accent me-2"></i>
                        <a href="tel:{{ $company['phone'] }}">{{ $company['phone'] }}</a>
                    </li>
                    <li>
                        <i class="bi bi-envelope text-accent me-2"></i>
                        <a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="py-3 text-center small text-secondary border-top border-secondary border-opacity-25">
        &copy; {{ date('Y') }} {{ $company['name'] }}. Hak cipta dilindungi undang-undang.
    </div>
</footer>