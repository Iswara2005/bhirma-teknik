@extends('layouts.admin')
@section('title', 'Teks Halaman')

@section('content')

<div class="page-head">
    <div>
        <h1 class="page-title">Teks Halaman</h1>
        <p class="page-sub">
            Atur tulisan di halaman website. Kosongkan kolom untuk memakai teks bawaan. Foto latar diganti lewat
            <a href="{{ route('admin.images.index') }}" class="text-accent-dark fw-semibold">Gambar Situs</a>,
            foto galeri lewat <a href="{{ route('admin.gallery.index') }}" class="text-accent-dark fw-semibold">Galeri</a>.
        </p>
    </div>
</div>

{{-- Tab halaman: muncul otomatis untuk setiap halaman yang terdaftar di App\Support\PageTexts --}}
@if ($tabs->count() > 1)
    <div class="d-flex flex-wrap gap-2 mb-4">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('admin.pages.edit', $key) }}"
               @class(['btn btn-sm', 'btn-dark' => $key === $page, 'btn-outline-dark' => $key !== $page])>{{ $label }}</a>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('admin.pages.update', $page) }}" novalidate>
    @csrf @method('PUT')

    @foreach ($groups as $group)
        <div class="panel panel-edit mb-4">
            <div class="panel-head">
                <span class="panel-icon is-dark"><i class="bi {{ $group['icon'] }}"></i></span>
                <div>
                    <h2>{{ $group['title'] }}</h2>
                    <p>{{ $group['desc'] }}</p>
                </div>
            </div>

            <div class="panel-body">
                {{-- Gambar pratinjau bagian ini (diatur di App\Support\PagePreviews) --}}
                @if (isset($previews[$group['title']]))
                    @include('admin.partials.page-preview', ['spec' => $previews[$group['title']]])
                @endif

                <div class="row g-3">
                    @foreach ($group['fields'] as $name => $field)
                        @php $area = $field['area'] ?? false; @endphp
                        <div class="{{ $area ? 'col-12' : 'col-md-6' }}">
                            <label for="f-{{ $name }}" class="form-label">{{ $field['label'] }}</label>
                            @if ($area)
                                <textarea id="f-{{ $name }}" name="{{ $name }}" rows="3" maxlength="{{ $field['max'] }}"
                                          placeholder="{{ $defaults[$name] }}"
                                          @class(['form-control', 'is-invalid' => $errors->has($name)])>{{ old($name, $text[$name] ?? '') }}</textarea>
                            @else
                                <input id="f-{{ $name }}" name="{{ $name }}" type="text" maxlength="{{ $field['max'] }}"
                                       value="{{ old($name, $text[$name] ?? '') }}" placeholder="{{ $defaults[$name] }}"
                                       @class(['form-control', 'is-invalid' => $errors->has($name)])>
                            @endif
                            @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <button class="btn btn-danger mb-4"><i class="bi bi-check-lg me-1"></i>Simpan teks</button>
</form>
@endsection

@push('scripts')
<script>
    // Pratinjau: ikut berubah saat mengetik (kolom kosong = teks bawaan),
    // dan bagian yang sedang diisi diberi garis merah.
    document.querySelectorAll('[data-pv]').forEach(el => {
        const field = el.dataset.pv;
        const input = document.getElementById('f-' + field);
        if (!input) return;

        const sync = () => { el.textContent = input.value.trim() || el.dataset.default; };
        input.addEventListener('input', sync);
        input.addEventListener('focus', () => el.classList.add('pv-hot'));
        input.addEventListener('blur', () => el.classList.remove('pv-hot'));
        sync();
    });
</script>
@endpush