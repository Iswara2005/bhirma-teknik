@extends('layouts.admin')
@section('title', 'Profil Saya')

@section('content')
<div class="page-head">
    <div>
        <h1 class="page-title">Profil Saya</h1>
        <p class="page-sub">Atur identitas akun admin dan keamanan login Anda.</p>
    </div>
</div>

<div class="row g-4 align-items-start">
    {{-- ===== Data profil ===== --}}
    <div class="col-lg-7">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="panel" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="panel-head">
                <span class="panel-icon"><i class="bi bi-person"></i></span>
                <div>
                    <h2>Data profil</h2>
                    <p>Foto, nama, dan email akun admin.</p>
                </div>
            </div>

            <div class="panel-body">
                {{-- Foto profil (atau ikon orang bila belum ada foto) --}}
                <div class="avatar-field mb-4">
                    <div class="avatar-lg">
                        <img id="avatar-preview" src="{{ $user->photoUrl() }}" alt="Foto profil" @unless ($user->photo) hidden @endunless>
                        <i id="avatar-empty" class="bi bi-person-circle" @if ($user->photo) hidden @endif></i>
                    </div>
                    <div class="flex-grow-1 min-w-220">
                        <label for="image" class="form-label">Foto profil</label>
                        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"
                               @class(['form-control', 'form-control-sm', 'is-invalid' => $errors->has('image')])>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">JPG, PNG, atau WebP, maksimal 4 MB. Otomatis dipotong persegi di tengah.</div>
                        @if ($user->photo)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                <label class="form-check-label small" for="remove_photo">Hapus foto saat ini</label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input id="name" name="name" type="text" maxlength="100" value="{{ old('name', $user->name) }}" autocomplete="name"
                           @class(['form-control', 'is-invalid' => $errors->has('name')])>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input id="email" name="email" type="email" maxlength="255" value="{{ old('email', $user->email) }}" autocomplete="username"
                           @class(['form-control', 'is-invalid' => $errors->has('email')])>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Email ini dipakai untuk login. Bila diubah, login berikutnya memakai email yang baru.</div>
                </div>
            </div>

            <div class="panel-foot">
                <button class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Simpan profil</button>
            </div>
        </form>
    </div>

    {{-- ===== Ganti password ===== --}}
    <div class="col-lg-5">
        <form method="POST" action="{{ route('admin.profile.password') }}" class="panel" novalidate>
            @csrf
            @method('PUT')

            <div class="panel-head">
                <span class="panel-icon is-dark"><i class="bi bi-shield-lock"></i></span>
                <div>
                    <h2>Ganti password</h2>
                    <p>Gunakan minimal 8 karakter.</p>
                </div>
            </div>

            <div class="panel-body">
                @foreach ([
                    ['current_password', 'Password lama', 'current-password'],
                    ['password', 'Password baru', 'new-password'],
                    ['password_confirmation', 'Ulangi password baru', 'new-password'],
                ] as [$field, $label, $autocomplete])
                    <div @class(['mb-3' => ! $loop->last])>
                        <label for="{{ $field }}" class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input id="{{ $field }}" name="{{ $field }}" type="password" autocomplete="{{ $autocomplete }}"
                                   @class(['form-control', 'is-invalid' => $errors->has($field)])>
                            <button type="button" class="btn btn-outline-secondary" data-toggle-password="#{{ $field }}"
                                    aria-label="Tampilkan / sembunyikan password" title="Tampilkan / sembunyikan password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error($field) <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                @endforeach
            </div>

            <div class="panel-foot">
                <button class="btn btn-dark"><i class="bi bi-key me-1"></i>Ganti password</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pratinjau foto profil saat file dipilih
    document.getElementById('image').addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        const preview = document.getElementById('avatar-preview');
        preview.src = URL.createObjectURL(file);
        preview.hidden = false;
        document.getElementById('avatar-empty').hidden = true;
        const remove = document.getElementById('remove_photo');
        if (remove) remove.checked = false;
    });

    // Tombol mata: tampilkan / sembunyikan isi kolom password
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.dataset.togglePassword);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });
</script>
@endpush