{{-- Satu potongan pratinjau. Dipanggil berulang oleh page-preview.blade.php --}}
@php $d = fn (?string $f) => $defaults[$f] ?? ''; @endphp
@switch($n['t'])
    @case('eyebrow')
        <p class="pv-eyebrow" data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</p>
        @break

    @case('heading')
        <p class="pv-h {{ $n['size'] === 'xl' ? 'pv-h-xl' : 'pv-h-lg' }}">
            {{ $n['prefix'] }}@foreach ($n['parts'] as $part)<span @class(['pv-accent' => $part[1] ?? false]) data-pv="{{ $part[0] }}" data-default="{{ $d($part[0]) }}">{{ $d($part[0]) }}</span> @endforeach
            @if ($n['sub'])
                <span class="pv-sub">{{ $n['sub']['prefix'] }}<span data-pv="{{ $n['sub']['f'] }}" data-default="{{ $d($n['sub']['f']) }}">{{ $d($n['sub']['f']) }}</span></span>
            @endif
        </p>
        @break

    @case('lead')
        <p class="pv-lead" data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</p>
        @break

    @case('text')
        <p class="pv-text" data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</p>
        @break

    @case('small')
        <p class="pv-note" data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</p>
        @break

    @case('link')
        <p class="pv-link"><span data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</span> &rarr;</p>
        @break

    @case('ctitle')
        @isset($n['f'])
            <p class="pv-ctitle" data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</p>
        @endisset
        @break

    @case('cdesc')
        <p class="pv-cdesc" data-pv="{{ $n['f'] }}" data-default="{{ $d($n['f']) }}">{{ $d($n['f']) }}</p>
        @break

    @case('static')
        @php $as = $n['as']; @endphp
        <p class="{{ ['label' => 'pv-label', 'num' => 'pv-num', 'ctitle' => 'pv-ctitle pv-fixed', 'cdesc' => 'pv-cdesc pv-fixed'][$as] ?? 'pv-fixed' }}">{{ $n['text'] }}</p>
        @break

    @case('ph')
        <div class="pv-ph" style="height: {{ $n['h'] }}px"><span>{{ $n['label'] }}</span></div>
        @break

    @case('btns')
        <div class="pv-btns">
            @foreach ($n['items'] as [$f, $kind])
                <span class="pv-btn pv-btn-{{ $kind }}" data-pv="{{ $f }}" data-default="{{ $d($f) }}">{{ $d($f) }}</span>
            @endforeach
        </div>
        @break

    @case('cards')
        <div class="pv-cards pv-cols-{{ $n['cols'] }}">
            @foreach ($n['items'] as $card)
                <div class="pv-card pv-card-{{ $card['tone'] }}"
                     @if (! empty($card['img']) && ! empty($images[$card['img']])) style="background-image: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url('{{ $images[$card['img']] }}');" @endif>
                    @foreach ($card['nodes'] as $child)
                        @include('admin.partials.pv-node', ['n' => $child])
                    @endforeach
                </div>
            @endforeach
        </div>
        @break

    @case('split')
        <div class="pv-split">
            <div>
                @foreach ($n['main'] as $child)
                    @include('admin.partials.pv-node', ['n' => $child])
                @endforeach
            </div>
            <div class="pv-photo" @if (! empty($images[$n['image']])) style="background-image: url('{{ $images[$n['image']] }}');" @endif></div>
        </div>
        @break
@endswitch