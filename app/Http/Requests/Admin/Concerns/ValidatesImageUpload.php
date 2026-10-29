<?php

namespace App\Http\Requests\Admin\Concerns;

trait ValidatesImageUpload
{
    /** Batas ukuran file unggahan (KB). Server juga punya batas sendiri di php.ini. */
    protected int $maxImageKb = 4096;

    /** @return array<int, string> */
    protected function imageRules(bool $required): array
    {
        return [
            $required ? 'required' : 'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:'.$this->maxImageKb,
        ];
    }

    /** @return array<string, string> */
    protected function imageMessages(): array
    {
        return [
            'image.required' => 'Pilih file gambar terlebih dahulu.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal '.($this->maxImageKb / 1024).' MB.',
            // Muncul bila file lebih besar dari batas upload_max_filesize di php.ini (bawaan XAMPP: 2 MB).
            'image.uploaded' => 'Gambar gagal diunggah. Ukurannya kemungkinan melebihi batas server (biasanya 2 MB); coba perkecil ukuran file.',
        ];
    }
}
