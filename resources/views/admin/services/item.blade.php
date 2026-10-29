{{-- Satu baris produk di form bidang usaha. $key = nomor unik baris (atau __KEY__ untuk templat JS). --}}
@php
    $points = data_get($item, 'points', '');
    $points = is_array($points) ? implode("\n", $points) : $points;
@endphp
<div class="item-card" data-item>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="item-title" data-item-title>Produk</span>
        <button type="button" class="btn btn-outline-danger btn-sm icon-btn" data-remove-item aria-label="Hapus produk ini" title="Hapus produk ini">
            <i class="bi bi-trash"></i>
        </button>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="item-{{ $key }}-name" class="form-label">Nama produk <span class="text-danger">*</span></label>
            <input id="item-{{ $key }}-name" name="items[{{ $key }}][name]" type="text" maxlength="100"
                   value="{{ data_get($item, 'name') }}" placeholder="Contoh: Jersey home"
                   @class(['form-control', 'is-invalid' => $errors->has("items.$key.name")])>
            @error("items.$key.name") <div class="invalid-feedback">{{ $message }}</div> @enderror

            <label for="item-{{ $key }}-desc" class="form-label mt-3">Deskripsi produk <span class="text-danger">*</span></label>
            <textarea id="item-{{ $key }}-desc" name="items[{{ $key }}][desc]" rows="3" maxlength="300"
                      @class(['form-control', 'is-invalid' => $errors->has("items.$key.desc")])>{{ data_get($item, 'desc') }}</textarea>
            @error("items.$key.desc") <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label for="item-{{ $key }}-points" class="form-label">Poin singkat</label>
            <textarea id="item-{{ $key }}-points" name="items[{{ $key }}][points]" rows="6"
                      placeholder="Ukuran S sampai XXL&#10;Bahan dry-fit&#10;Tersedia banyak klub"
                      @class(['form-control', 'is-invalid' => $errors->has("items.$key.points") || $errors->has("items.$key.points.*")])>{{ $points }}</textarea>
            @error("items.$key.points") <div class="invalid-feedback">{{ $message }}</div> @enderror
            @error("items.$key.points.*") <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">Satu poin (maksimal 10). Tampil sebagai daftar centang.</div>
        </div>
    </div>
</div>