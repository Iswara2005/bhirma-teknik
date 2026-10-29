<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') | CMS {{ config('company.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="bg-surface @auth admin-app @else admin-login @endauth">

@auth
    @php
        $menu = [
            'admin.dashboard'      => ['Dashboard', 'bi-speedometer2'],
            'admin.pages.edit'     => ['Teks Halaman', 'bi-file-earmark-text'],
            'admin.images.index'   => ['Gambar Situs', 'bi-images'],
            'admin.services.index' => ['Bidang Usaha', 'bi-grid'],
            'admin.gallery.index'  => ['Galeri', 'bi-collection'],
            'admin.company.edit'   => ['Kontak & Alamat', 'bi-geo-alt'],
        ];
        $logo = \App\Models\SiteImage::urls()['logo'];
    @endphp

    {{-- Kolom kiri (sidebar) + kolom isi. Di HP, sidebar jadi menu geser dari kiri. --}}
    <div class="d-lg-flex min-vh-100">

        <div class="admin-sidebar-col">
            <div class="offcanvas-lg offcanvas-start admin-sidebar-panel" tabindex="-1" id="adminSidebar" aria-label="Menu admin">
                <div class="offcanvas-header justify-content-end pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Tutup menu"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column">
                    {{-- Merek: logo + nama perusahaan --}}
                    <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                        <span class="admin-brand-logo"><img src="{{ $logo }}" alt=""></span>
                        <span>
                            <span class="admin-brand-name d-block">{{ config('company.name') }}</span>
                            <span class="admin-brand-sub">Panel admin</span>
                        </span>
                    </a>

                    <nav class="nav flex-column admin-menu" aria-label="Menu utama">
                        <span class="admin-menu-label">Kelola website</span>
                        @foreach ($menu as $route => [$label, $icon])
                            <a href="{{ route($route) }}" @class(['nav-link', 'active' => request()->routeIs($route, str_replace('.index', '.*', $route))])>
                                <i class="bi {{ $icon }}"></i>{{ $label }}
                            </a>
                        @endforeach

                        <span class="admin-menu-label">Lainnya</span>
                        <a href="{{ route('admin.profile.edit') }}" @class(['nav-link', 'active' => request()->routeIs('admin.profile.*')])>
                            <i class="bi bi-person-gear"></i>Profil
                        </a>
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="nav-link">
                            <i class="bi bi-box-arrow-up-right"></i>Lihat Website
                                                    </a>
                    </nav>

                    {{-- Akun di dasar sidebar: klik nama untuk profil, ikon untuk keluar --}}
                    <div class="mt-auto admin-sidebar-foot">
                        <div @class(['admin-user', 'active' => request()->routeIs('admin.profile.*')])>
                            <a href="{{ route('admin.profile.edit') }}" class="admin-user-link" title="Pengaturan profil">
                                @if ($photo = auth()->user()->photoUrl())
                                    <img src="{{ $photo }}" alt="Foto profil" class="admin-avatar">
                                @else
                                    <i class="bi bi-person-circle admin-avatar-icon"></i>
                                @endif
                                <span class="min-w-0">
                                    <span class="name d-block text-truncate">{{ auth()->user()->name }}</span>
                                    <span class="mail d-block text-truncate">{{ auth()->user()->email }}</span>
                                </span>
                            </a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="admin-logout" title="Keluar" aria-label="Keluar"><i class="bi bi-box-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-grow-1 min-w-0">
            {{-- Bilah atas khusus HP: tombol buka menu --}}
            <header class="d-lg-none admin-topbar d-flex align-items-center gap-3 px-3 py-2">
                <button type="button" class="menu-btn" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Buka menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <span class="fw-semibold text-truncate">{{ config('company.name') }}</span>
            </header>
@endauth

<main @class(['admin-main' => auth()->check()])>
    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i><div>{{ session('status') }}</div>
        </div>
    @endif

    @yield('content')
</main>

@auth
        </div>{{-- /kolom isi --}}
    </div>{{-- /d-lg-flex --}}
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Pratinjau gambar sebelum diunggah: <input type="file" data-preview="#idGambar">
    document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
        input.addEventListener('change', () => {
            const img = document.querySelector(input.dataset.preview);
            const file = input.files[0];
            if (img && file) img.src = URL.createObjectURL(file);
        });
    });
</script>
@stack('scripts')
</body>
</html>