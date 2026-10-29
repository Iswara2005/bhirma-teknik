<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesImageUpload;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    use ValidatesImageUpload;

    public function authorize(): bool
    {
        return true; // sudah dijaga middleware "auth" pada route
    }

    /**
     * Daftar produk dikirim sebagai items[n][name|desc|points]. Di sini baris yang
     * sepenuhnya kosong dibuang dan "poin" (satu per baris) diubah jadi array,
     * sehingga validasi & penyimpanan menerima data yang sudah rapi.
     */
    protected function prepareForValidation(): void
    {
        $rows = $this->input('items');
        if (! is_array($rows)) {
            return;
        }

        $clean = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $desc = trim((string) ($row['desc'] ?? ''));
            $points = $row['points'] ?? '';
            $lines = is_array($points) ? $points : preg_split('/\R/', (string) $points);
            $lines = array_values(array_filter(
                array_map(fn ($line) => trim((string) $line), $lines),
                fn ($line) => $line !== '',
            ));

            if ($name === '' && $desc === '' && $lines === []) {
                continue; // baris kosong = diabaikan
            }

            $clean[] = ['name' => $name, 'desc' => $desc, 'points' => $lines];
        }

        $this->merge(['items' => $clean]);
    }

    public function rules(): array
    {
        // Saat mengedit (ada {service} di URL), gambar boleh dikosongkan = tetap pakai yang lama.
        $isCreating = $this->route('service') === null;

        return [
            'name' => ['required', 'string', 'max:100'],
            'desc' => ['required', 'string', 'max:300'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
            'image' => $this->imageRules(required: $isCreating),

            // Detail produk (tab di halaman bidang usaha)
            'items' => ['nullable', 'array', 'max:30'],
            'items.*.name' => ['required', 'string', 'max:100'],
            'items.*.desc' => ['required', 'string', 'max:300'],
            'items.*.points' => ['nullable', 'array', 'max:10'],
            'items.*.points.*' => ['string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return $this->imageMessages() + [
            'name.required' => 'Nama usaha wajib diisi.',
            'name.max' => 'Nama usaha maksimal 100 karakter.',
            'desc.required' => 'Deskripsi wajib diisi.',
            'desc.max' => 'Deskripsi maksimal 300 karakter.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan tidak boleh negatif.',
            'items.max' => 'Produk maksimal 30 per bidang usaha.',
            'items.*.name.required' => 'Nama produk wajib diisi.',
            'items.*.name.max' => 'Nama produk maksimal 100 karakter.',
            'items.*.desc.required' => 'Deskripsi produk wajib diisi.',
            'items.*.desc.max' => 'Deskripsi produk maksimal 300 karakter.',
            'items.*.points.max' => 'Poin maksimal 10 baris per produk.',
            'items.*.points.*.max' => 'Setiap poin maksimal 150 karakter.',
        ];
    }
}