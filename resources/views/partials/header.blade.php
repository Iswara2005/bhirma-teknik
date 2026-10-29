@php
    $menu = [
        'home'     => 'Beranda',
        'about'    => 'Tentang Kami',
        'services' => 'Bidang Usaha',
        'gallery'  => 'Galeri',
        'contact'  => 'Kontak',
    ];
@endphp

<nav class="navbar navbar-expand-lg navbar-light navbar-bt sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="logo-badge rounded-0"><img src="{{ $images['logo'] }}" alt="Logo {{ $company['name'] }}"></span>
            <span class="lh-sm">
                <span class="d-block fw-bold fs-6 text-dark">{{ $company['name'] }}</span>
                <small class="d-block text-secondary brand-city">{{ $company['city'] }}</small>
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 py-3 py-lg-0">
                @foreach ($menu as $route => $label)
                    <li class="nav-item">
                        {{-- "services.*" membuat menu Bidang Usaha tetap aktif di halaman detail --}}
                        <a href="{{ route($route) }}" @class(['nav-link', 'active' => request()->routeIs($route, "$route.*")])>{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>