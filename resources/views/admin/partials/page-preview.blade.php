{{-- Gambar pratinjau satu kelompok teks. Data: $spec (App\Support\PagePreviews), $defaults, $images --}}
<div class="pv-wrap mb-4">
    <p class="form-text mb-2">
        <i class="bi bi-eye me-1"></i>Tampilan di website: <strong>{{ $spec['where'] }}</strong>.
        Klik kolom di bawah, bagian yang berubah akan diberi garis merah.
    </p>
    <div class="pv-frame">
        <div class="pv-bar"><i></i><i></i><i></i><span>Pratinjau</span></div>
        <div class="pv pv-{{ $spec['theme'] }}"
             @if (! empty($spec['bg']) && ! empty($images[$spec['bg']]))
                 style="background-image: linear-gradient(rgba(0,0,0,.58), rgba(0,0,0,.58)), url('{{ $images[$spec['bg']] }}');"
             @endif>
            <div class="pv-in {{ ($spec['align'] ?? 'left') === 'center' ? 'pv-center' : '' }}">
                @foreach ($spec['nodes'] as $node)
                    @include('admin.partials.pv-node', ['n' => $node])
                @endforeach
            </div>
        </div>
    </div>
</div>