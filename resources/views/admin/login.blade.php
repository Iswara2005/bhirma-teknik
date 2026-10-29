@extends('layouts.admin')
@section('title', 'Masuk')

@section('content')
@php
    // Logo dan foto latar mengikuti yang diatur admin di menu "Gambar Situs" (atau gambar bawaan)
    $images = \App\Models\SiteImage::urls();
    $logo = $images['logo'];
    $hero = $images['hero'];
@endphp

<div class="auth-shell">
    {{-- Panel kiri (hanya layar lebar): foto perusahaan, tanpa teks promosi --}}
    <aside class="auth-side" style="--auth-photo: url('{{ $hero }}')">
        <div class="admin-brand p-0">
            <span class="admin-brand-logo"><img src="{{ $logo }}" alt=""></span>
            <span>
                <span class="admin-brand-name d-block">{{ config('company.name') }}</span>
                <span class="admin-brand-sub">{{ config('company.city') }}</span>
            </span>
        </div>
        <small class="auth-copy">&copy; {{ date('Y') }} {{ config('company.name') }}</small>
    </aside>

    {{-- Formulir masuk --}}
    <main class="auth-form-wrap">
        <div class="auth-card">
            {{-- Logo untuk HP (di layar lebar logo ada di panel kiri) --}}
            <img src="{{ $logo }}" alt="Logo {{ config('company.name') }}" class="auth-logo-mobile">

            <header class="mb-4">
                <h1 class="auth-title">Masuk</h1>
                <p class="text-secondary mb-0">Panel admin {{ config('company.name') }}</p>
            </header>

            <form method="POST" action="{{ route('admin.login') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           autocomplete="username" autofocus
                           aria-describedby="email-error"
                           @class(['form-control', 'is-invalid' => $errors->has('email')])>
                    @error('email')
                        <div id="email-error" class="auth-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="auth-password">
                        <input id="password" name="password" type="password" autocomplete="current-password"
                               aria-describedby="password-error"
                               @class(['form-control', 'is-invalid' => $errors->has('password')])>
                        <button type="button" id="togglePassword" class="auth-eye"
                                aria-label="Tampilkan password" aria-pressed="false">
                            <i class="bi bi-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('password')
                        <div id="password-error" class="auth-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Tetap masuk di perangkat ini</label>
                </div>

                <button class="btn btn-danger btn-lg w-100" type="submit">Masuk</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="link-secondary small text-decoration-none">Kembali ke website</a>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Tombol mata: tampilkan / sembunyikan password
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        this.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        this.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        this.setAttribute('aria-pressed', show ? 'true' : 'false');
    });
</script>
@endpush