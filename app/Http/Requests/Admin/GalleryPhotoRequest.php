<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesImageUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryPhotoRequest extends FormRequest
{
    use ValidatesImageUpload;

    public function authorize(): bool
    {
        return true; // sudah dijaga middleware "auth" pada route
    }

    public function rules(): array
    {
        // Saat mengedit (ada {photo} di URL), foto boleh dikosongkan = tetap pakai yang lama.
        $photo = $this->route('photo');
        $isCreating = $photo === null;

        // Kategori hanya boleh dari daftar di config/gallery.php. Kategori lama milik foto
        // yang sedang diedit (mis. "Produk") tetap diterima, supaya foto lama bisa disimpan.
        $categories = config('gallery.categories');
        if ($photo?->category) {
            $categories[] = $photo->category;
        }

        return [
            'title' => ['nullable', 'string', 'max:100'],
            'category' => ['required', 'string', Rule::in($categories)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
            'image' => $this->imageRules(required: $isCreating),
        ];
    }

    public function messages(): array
    {
        return $this->imageMessages() + [
            'title.max' => 'Judul foto maksimal 100 karakter.',
            'category.required' => 'Pilih kategori foto.',
            'category.in' => 'Kategori tidak valid. Pilih salah satu dari daftar.',
            'sort_order.integer' => 'Urutan harus berupa angka.',
            'sort_order.min' => 'Urutan tidak boleh negatif.',
            'sort_order.max' => 'Urutan maksimal 9999.',
        ];
    }
}